<script setup>
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import { Link, useForm, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { useToast } from "vue-toast-notification";
import InventoryTransactionForm from "./InventoryTransactionForm.vue";
import TitleAndButtonsWrapper from "@/Components/Partials/TitleAndButtonsWrapper.vue";
import DataTable from "@/Components/Data/DataTable.vue";
import dayjs from "dayjs";
import { debounce } from 'lodash';
const $toast = useToast({ position: "top-right" });

/* Props Definitions */
const props = defineProps({
  item: Object,
  transactions: Object,
  itemLotNumbers: Object,
});

/**
 * Data Definitions
*/

/* DataTable: Pagination and Filters */
const urlParams = new URLSearchParams(window.location.search);
const currentPage = Number(urlParams.get("page")) || 1;
const filters = JSON.parse(urlParams.get("column_filters"));

/* DataTable Data: Transactions Definitions (For DataTable) */
const transactions = ref(props.transactions);

/* DataTable Filtering: Return Filter Value by ID */
const getFilter = (id) => filters?.find((filter) => filter.field === id)?.value;

/* DataTable: Field Columns */
const columns = ref([
  { field: "id", title: "ID", value: getFilter("id") },
  { field: "lot_number", title: "Lot No.", value: getFilter("lot_number") },
  { field: "quantity", title: "Quantity", value: getFilter("quantity") },
  { field: "date_received", title: "Date Received", value: getFilter("date_received") },
  { field: "date_opened", title: "Date Opened", value: getFilter("date_opened") },
  { field: "expiration_date", title: "Expiration", value: getFilter("expiration_date") },
  { field: "stock", title: "Stock", filter: false },
  { field: "created_at", title: "Date", value: getFilter("created_at") },
]);

/* Transaction Form: Increase or Decrease Toggle Definitions */
const showForm = ref(false);
const transactionType = ref("INCREASE");

/**
 *  Methods
 */

/* Transaction Form: Transaction Form Toggle */
const toggleTransactionForm = (type) => {
  showForm.value = !showForm.value;
  transactionType.value = type;
};

/* Transaction Form: Refresh transactions when new ones are added */
const refreshItems = () => {
  router.visit(route("inventory.show", props.item.id), {
    only: ["transactions"],
  });
};

/* Inventory Item: Delete Inventory Item */
const deleteItem = () => {
  if (confirm("Are you sure you want to delete?")) {
    const form = useForm({});
    form.delete(route("inventory.destroy", props.item.id), {
      errorBag: "deleteItem",
      preserveScroll: true,
      onSuccess: () => $toast.success("Item Deleted Successfully!"),
    });
  }
};

/* DataTable: Fetch Updated Data upon Filtering or Page Change */
const updateRows = debounce((event) => {
  const column_filters = JSON.stringify(event.column_filters);
  router.get(
    route("inventory.show", props.item.id),
    { page: event.current_page, column_filters },
    {
      preserveScroll: true,
      preserveState: true,
    }
  );
}, 500);

/* DataTable: Update Transactions on Table Change */
watch(
  () => props.transactions,
  (newData) => {
    transactions.value = newData;
  }
);
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
        <InventoryTransactionForm
          :item="item"
          :transaction-type="transactionType"
          :item-lot-numbers="itemLotNumbers"
          @form-submitted="refreshItems"
        />
      </div>

      <DataTable
        class="mt-2.5"
        :rows="transactions.data"
        :columns="columns"
        :total-rows="transactions.total"
        :current-page="currentPage"
        :page-size="transactions.per_page"
        :column-filter="true"
        :page-change-fn="updateRows"
      >
        <template #id="{ data }">
          <Link
            class="hover:underline"
            :href="route('inventory-transactions.show', data.value.id)"
            >{{ data.value.id }}</Link
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

        <template #created_at="{ data }">
          <span>{{ dayjs(data.value.created_at).format("YYYY-MM-DD") }}</span>
        </template>
      </DataTable>
    </div>
  </div>
</template>
