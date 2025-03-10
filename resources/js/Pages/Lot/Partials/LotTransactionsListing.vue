<script setup>
import LabelAndValue from "@/Components/Patients/LabelAndValue.vue";
import { useToast } from "vue-toast-notification";
import TitleAndButtonsWrapper from "@/Components/Partials/TitleAndButtonsWrapper.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DataTable from "@/Components/Data/DataTable.vue";
import { ref, watch } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import { debounce } from "lodash";

const $toast = useToast({ position: "top-right" });

const props = defineProps({
  lotTransactions: Object,
  primaryTransaction: Object,
});

/* DataTable: Pagination and Filters */
const urlParams = new URLSearchParams(window.location.search);
const currentPage = Number(urlParams.get("page")) || 1;
const filters = JSON.parse(urlParams.get("column_filters"));

/* DataTable Data: Transactions Definitions (For DataTable) */
const transactions = ref(props.lotTransactions);

/* DataTable Filtering: Return Filter Value by ID */
const getFilter = (id) => filters?.find((filter) => filter.field === id)?.value;

const columns = ref([
  { field: "id", title: "ID", value: getFilter("id") },
  { field: "lot_number", title: "Lot No.", value: getFilter("lot_number") },
  { field: "quantity", title: "Quantity", value: getFilter("quantity") },
  { field: "date_received", title: "Date Received", value: getFilter("date_received") },
  { field: "date_opened", title: "Date Opened", value: getFilter("date_opened") },
  { field: "expiration_date", title: "Expiration", value: getFilter("expiration_date") },
  { field: "stock", title: "Stock", filter: false },
  { field: "created_at_formatted", title: "Date", filter: false },
]);

/* DataTable: Fetch Updated Data upon Filtering or Page Change */
const updateRows = debounce((event) => {
  const column_filters = JSON.stringify(event.column_filters);
  router.get(
    route("inventory-transactions.lot-listing", props.primaryTransaction.lot_number),
    { page: event.current_page, column_filters },
    {
      preserveScroll: true,
      preserveState: true,
    }
  );
}, 500);

/* DataTable: Update Transactions on Table Change */
watch(
  () => props.lotTransactions,
  (newData) => {
    transactions.value = newData;
  }
);

const archiveLot = () => {
  if (confirm("Are you sure you want to archive?")) {
    const form = useForm({});
    form.post(route("inventory-transactions.archive-lot", props.primaryTransaction.lot_number), {
      errorBag: "archiveLot",
      preserveScroll: true,
      onSuccess: () => $toast.success("Item Archived Successfully!"),
    });
  }
};
</script>
<template>
  <div>
    <!-- <h1>Inventory Transaction for {{ transaction.inventory_item.name }}</h1> -->
    <!-- Inventory Transaction -->
    <div
      class="bg-white rounded-md mt-2.5 md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <div class="flex md:flex-row flex-col md:gap-x-4 gap-x-3">
        <div class="w-full">
          <TitleAndButtonsWrapper>
            <h2 class="leading-none md:text-[1.5rem] text-[1.2rem]">
              Transactions for Lot Number: {{ primaryTransaction.lot_number }}
            </h2>
            <div class="flex gap-[.5rem]">
              <PrimaryButton @click="archiveLot" size="small" type="button" v-if="!primaryTransaction.is_archived">
                <span>Archive</span>
              </PrimaryButton>
              <SecondaryButton size="small" type="button" v-else>
                <span>Archived</span>
              </SecondaryButton>
            </div>
          </TitleAndButtonsWrapper>
          <div
            class="grid lg:grid-cols-3 md:grid-cols-2 md:gap-x-6 md:gap-y-4 gap-3 md:mt-4 mt-6"
          >
            <LabelAndValue
              label="Quantity"
              v-if="primaryTransaction.quantity"
              :value="String(primaryTransaction.quantity)"
            />

            <LabelAndValue
              label="Transaction Date"
              :value="primaryTransaction.created_at_formatted"
            />

            <LabelAndValue
              label="Expiration Date"
              v-if="primaryTransaction.expiration_date"
              :value="primaryTransaction.expiration_date"
            />

            <LabelAndValue
              label="Date Received"
              v-if="primaryTransaction.date_received"
              :value="primaryTransaction.date_received"
            />

            <LabelAndValue
              label="Date Opened"
              v-if="primaryTransaction.date_opened"
              :value="primaryTransaction.date_opened"
            />

            <LabelAndValue label="Notes" :value="primaryTransaction.notes" />
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
          </DataTable>
        </div>
      </div>
    </div>
  </div>
</template>
