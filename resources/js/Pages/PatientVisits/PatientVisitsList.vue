<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import { ref, watch } from "vue";
import DataTable from "@/Components/Data/DataTable.vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import { debounce } from "lodash";
import dayjs from "dayjs";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import { useToast } from "vue-toast-notification";

const $toast = useToast({ position: "top-right" });


const props = defineProps({
  visits: Object,
  search: String,
});

const selectedRows = ref([]);

/* DataTable: Pagination and Filters */
const urlParams = new URLSearchParams(window.location.search);
const currentPage = Number(urlParams.get("page")) || 1;
const filters = JSON.parse(urlParams.get("column_filters"));

/* DataTable Data: Transactions Definitions (For DataTable) */
const visits = ref(props.visits);

/* DataTable Filtering: Return Filter Value by ID */
const getFilter = (id) => filters?.find((filter) => filter.field === id)?.value;

const columns = ref([
  { field: "patient.last_name", title: "Last Name", value: getFilter("last_name") },
  { field: "patient.first_name", title: "First Name", filter: false },
  { field: "patient.middle_name", title: "Middle Name", filter: false },
    { field: "invoice.is_paid", title: "Paid?", filter: false, },
  //   { field: "requesting_physician", title: "Requesting Physician" },
  { field: "patient_type", title: "Type" },
  { field: "office_type", title: "Office Type" },
  { field: "patient_status", title: "Status" },
  { field: "visit_date", title: "Visit Date", value: getFilter("vist_date") },
]);

/* DataTable: Fetch Updated Data upon Filtering or Page Change */
const updateRows = debounce((event) => {
  const column_filters = JSON.stringify(event.column_filters);
  router.get(
    route("patient-visits.index"),
    { page: event.current_page, column_filters, pagesize: event.pagesize },
    {
      preserveScroll: true,
      preserveState: true,
    }
  );
}, 500);

/* DataTable: Update Data on Table Change */
watch(
  () => props.visits,
  (newData) => {
    visits.value = newData;
  }
);

const markSelectedAsPaid = () => {
  const form = useForm({
    selected_ids: selectedRows.value,
  });
  form.post(route("patient-visits.mark-as-paid"), {
    errorBag: "updateStatus",
    preserveScroll: true,
    onSuccess: () => {
      $toast.success("Transactions Updated Successfully!");
    },
  });
};

const selectRows = (e) => {
  const rows = e;
  const selectedIds = rows.map((visit) => visit.id);
  selectedRows.value = selectedIds;
};

defineOptions({ layout: AdminLayout });
</script>
<template>
  <AdminContentWrapper>
    <div class="flex justify-between">
      <h1>Patient Transactions List</h1>
      <PrimaryButton size="small" color="green" @click="markSelectedAsPaid"
        >Mark as Paid</PrimaryButton
      >
    </div>
    <DataTable
      class="mt-2.5 p-[1rem]"
      :search="props.search"
      :hasSearch="true"
      :rows="visits.data"
      :columns="columns"
      :total-rows="visits.total"
      :current-page="currentPage"
      :page-size="visits.per_page"
      :column-filter="true"
      :page-change-fn="updateRows"
      :has-check-box="true"
      :select-row-on-click="true"
      :select-rows-fn="(e) => selectRows(e)"
    >
      <template #patient.last_name="{ data }">
        <Link
          class="text-blue-600 hover:underline"
          :href="route('patient-visits.show', data.value.id)"
          >{{ data.value.patient.last_name }}</Link
        >
      </template>
      <template #invoice.is_paid="{ data }">
        <span>{{ data.value.invoice ? (data.value.invoice?.is_paid ? 'Yes' : 'No' ) : 'No Invoice' }}</span>
      </template>
      <template #visit_date="{ data }">
        <span>{{ dayjs(data.value.visit_date).format("YYYY-MM-DD") }}</span>
      </template>
    </DataTable>
  </AdminContentWrapper>
</template>
