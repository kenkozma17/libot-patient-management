<script setup>
import LabelAndValue from "@/Components/Patients/LabelAndValue.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { useToast } from "vue-toast-notification";
import InventoryTransactionForm from "./InventoryTransactionForm.vue";
import TitleAndButtonsWrapper from "@/Components/Partials/TitleAndButtonsWrapper.vue";
import DataTable from "@/Components/Data/DataTable.vue";
import DangerButton from "@/Components/DangerButton.vue";

const $toast = useToast({ position: "top-right" });

const props = defineProps({
  item: Object,
  transactions: Object,
});

const showForm = ref(false);
const transactionType = ref("INCREASE");

const toggleTransactionForm = (type) => {
  showForm.value = !showForm.value;
  transactionType.value = type;
};

const form = useForm({});
const deleteItem = () => {
  if (confirm("Are you sure you want to delete?")) {
    form.delete(route("inventory.destroy", props.item.id), {
      errorBag: "deleteItem",
      preserveScroll: true,
      onSuccess: () => $toast.success("Item Deleted Successfully!"),
    });
  }
};

const columns = ref([
  { field: "lot_number", title: "Lot No." },
  { field: "patient_visit", title: "Patient Transaction ID" },
  { field: "quantity", title: "Quantity" },
    { field: "date_received", title: "Date Received" },
    { field: "date_opened", title: "Date Opened" },
    { field: "expiration_date", title: "Expiration" },
  { field: "notes", title: "Notes" },
  { field: "transaction_type", title: "Type" },
  { field: "stock", title: "Stock" },
  { field: "created_at_formatted", title: "Date" },
]);

const rows = ref(props.transactions.data);
</script>
<template>
  <div>
    <h1>Inventory</h1>
    <!-- Inventory Item -->
    <div
      class="bg-white rounded-md mt-2.5 md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <div class="flex md:flex-row flex-col md:gap-x-4 gap-x-3">
        <div class="w-full">
          <TitleAndButtonsWrapper>
            <div class="flex flex-wrap items-center gap-[.5rem]">
              <h2 class="leading-none md:text-[1.5rem] text-[1.2rem]">
                {{ item.name }}
              </h2>
              <span
                class="text-xs font-semibold rounded-md px-[.5rem] py-[.25rem] bg-green-300 inline-block"
              >
                Current Stock: {{ item.current_stock }} {{ item.unit }}
              </span>
              <span
                class="text-xs font-semibold rounded-md px-[.5rem] py-[.25rem] bg-purple-300 inline-block"
              >
                {{ item.classification.toUpperCase() }}
              </span>
              <span
                v-if="item.category.name"
                class="text-xs font-semibold rounded-md px-[.5rem] py-[.25rem] bg-yellow-400 inline-block"
              >
                {{ item.category.name }}
              </span>

              <span
                v-if="item.low_stock_limit"
                class="text-xs font-semibold rounded-md px-[.5rem] py-[.25rem] bg-red-400 inline-block"
              >
                Low Stock Notice: {{ item.low_stock_limit }}
              </span>

              <span
                v-if="item.days_before_expiration_limit"
                class="text-xs font-semibold rounded-md px-[.5rem] py-[.25rem] bg-blue-400 inline-block"
              >
                Expiration Notice: {{ item.days_before_expiration_limit }} day/s
              </span>
            </div>
            <div>
              <PrimaryButton size="small"
                ><Link :href="route('inventory.edit', item.id)"
                  >Edit Item</Link
                ></PrimaryButton
              >
              <PrimaryButton @click="deleteItem" size="small" class="ml-2" color="red"
                >Delete</PrimaryButton
              >
            </div>
          </TitleAndButtonsWrapper>
        </div>
      </div>
    </div>

    <!-- Inventory Transactions -->
    <div
      class="bg-white rounded-md md:mt-6 mt-3 md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <TitleAndButtonsWrapper>
        <h2 class="leading-none md:text-[1.5rem] text-[1.2rem]">Transactions</h2>
        <div class="flex gap-[0.5rem]">
          <PrimaryButton
            v-if="!showForm"
            class="md:mt-0 mt-2"
            size="small"
            @click="toggleTransactionForm('INCREASE')"
            >Increase
          </PrimaryButton>
          <PrimaryButton
            v-if="!showForm"
            class="md:mt-0 mt-2"
            size="small"
            color="red"
            @click="toggleTransactionForm('DECREASE')"
            >Decrease
          </PrimaryButton>
          <a href="#" @click="toggleTransactionForm" v-else>Cancel</a>
        </div>
      </TitleAndButtonsWrapper>

      <div
        class="border-black border-t border-b border-opacity-20 md:mt-[2rem] mt-[1rem]"
        v-if="showForm"
      >
        <InventoryTransactionForm :item="item" :transaction-type="transactionType" />
      </div>

      <DataTable class="mt-2.5" :rows="rows" :columns="columns">
        <template #lot_number="{ data }">
          <Link
            class="hover:underline"
            :href="route('inventory-transactions.show', data.value.id)"
            >{{ data.value.lot_number }}</Link
          >
        </template>
        <template #quantity="{ data }">
          <span
            class="font-bold"
            :class="
              data.value.transaction_type === 'INCREASE'
                ? 'text-green-400'
                : 'text-red-400'
            "
            >{{ data.value.quantity }}</span
          >
        </template>
        <template #patient_visit="{ data }">
          <Link
            class="hover:underline hover:text-blue-500"
            v-if="data.value.patient_visit_id"
            :href="route('patient-visits.show', data.value.patient_visit_id)"
            >{{ data.value.patient_visit_id }}</Link
          >
        </template>
      </DataTable>
    </div>
  </div>
</template>
