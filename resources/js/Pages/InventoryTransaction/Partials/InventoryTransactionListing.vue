<script setup>
import LabelAndValue from "@/Components/Patients/LabelAndValue.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { useToast } from "vue-toast-notification";
// import InventoryTransactionForm from "./InventoryTransactionForm.vue";
import TitleAndButtonsWrapper from "@/Components/Partials/TitleAndButtonsWrapper.vue";
import DataTable from "@/Components/Data/DataTable.vue";

const $toast = useToast({ position: "top-right" });

const props = defineProps({
  transaction: Object,
});

// const showForm = ref(false);

// const toggleTransactionForm = () => (showForm.value = !showForm.value);

// const form = useForm({});
// const deleteItem = () => {
//   if (confirm("Are you sure you want to delete?")) {
//     form.delete(route("inventory.destroy", props.item.id), {
//       errorBag: "deleteItem",
//       preserveScroll: true,
//       onSuccess: () => $toast.success("Item Deleted Successfully!"),
//     });
//   }
// };

// const columns = ref([
//   { field: "lot_number", title: "Lot No." },
//   { field: "quantity", title: "Quantity" },
//   { field: "date_received", title: "Date Received" },
//   { field: "date_opened", title: "Date Opened" },
//   { field: "expiration_date", title: "Expiration" },
// ]);

// const rows = ref(props.transactions.data);
</script>
<template>
  <div>
    <h1>Inventory Transaction for {{ transaction.inventory_item.name }}</h1>
    <!-- Inventory Transaction -->
    <div
      class="bg-white rounded-md mt-2.5 md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <div class="flex md:flex-row flex-col md:gap-x-4 gap-x-3">
        <div class="w-full">
          <TitleAndButtonsWrapper>
            <h2 class="leading-none md:text-[1.5rem] text-[1.2rem]">
              {{ transaction.id }} - {{ transaction.transaction_type }}
            </h2>
            <div>
              <!-- <PrimaryButton size="small"
                >
                <Link :href="route('inventory-transactions.edit', transaction.id)"
                  >Edit Item</Link
                ></PrimaryButton
              >
              <PrimaryButton @click="deleteItem" size="small" class="ml-2" color="red"
                >Delete</PrimaryButton
              > -->
            </div>
          </TitleAndButtonsWrapper>
          <div
            class="grid lg:grid-cols-3 md:grid-cols-2 md:gap-x-6 md:gap-y-4 gap-3 md:mt-4 mt-6"
          >
            <LabelAndValue
              label="Inventory Item"
              v-if="transaction.inventory_item.name"
              :value="transaction.inventory_item.name"
            />

            <LabelAndValue
              label="Lot Number"
              v-if="transaction.lot_number"
              :value="transaction.lot_number"
            />

            <LabelAndValue
              label="Quantity"
              v-if="transaction.quantity"
              :value="transaction.quantity"
            />

            <LabelAndValue
              label="Stock"
              v-if="transaction.stock"
              :value="transaction.stock"
            />

            <LabelAndValue
              label="Transaction Date"
              :value="transaction.created_at_formatted"
            />

            <LabelAndValue
              label="Notes"
              :value="transaction.notes"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
