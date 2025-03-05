<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import { ref, watch } from "vue";
import DataTable from "@/Components/Data/DataTable.vue";
import { Link, router } from "@inertiajs/vue3";
import { debounce } from "lodash";

const props = defineProps({
  visits: Object,
  search: String,
});

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
  { field: "patient.first_name", title: "First Name", filter: false, },
  { field: "patient.middle_name", title: "Middle Name", filter: false, },
  { field: "diagnosis", title: "Diagnosis" },
  { field: "requesting_physician", title: "Requesting Physician" },
  { field: "patient_type", title: "Type" },
  { field: "patient_status", title: "Status" },
  { field: "visit_date_formatted", title: "Visit Date", filter: false, },
]);

/* DataTable: Fetch Updated Data upon Filtering or Page Change */
const updateRows = debounce((event) => {
  const column_filters = JSON.stringify(event.column_filters);
  router.get(
    route("patient-visits.index"),
    { page: event.current_page, column_filters },
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

defineOptions({ layout: AdminLayout });
</script>
<template>
  <AdminContentWrapper>
    <h1>Patient Transactions List</h1>
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
    >
      <template #patient.last_name="{ data }">
        <Link
          class="text-blue-600 hover:underline"
          :href="route('patient-visits.show', data.value.id)"
          >{{ data.value.patient.last_name }}</Link
        >
      </template>
    </DataTable>
  </AdminContentWrapper>
</template>
