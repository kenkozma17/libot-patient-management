<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import { ref, watch } from "vue";
import DataTable from "@/Components/Data/DataTable.vue";
import { Link, router } from "@inertiajs/vue3";
import { debounce } from "lodash";

const props = defineProps({
  patients: Object,
  search: String,
});

/* DataTable: Pagination and Filters */
const urlParams = new URLSearchParams(window.location.search);
const currentPage = Number(urlParams.get("page")) || 1;
const filters = JSON.parse(urlParams.get("column_filters"));

/* DataTable Data: Transactions Definitions (For DataTable) */
const patients = ref(props.patients);

/* DataTable Filtering: Return Filter Value by ID */
const getFilter = (id) => filters?.find((filter) => filter.field === id)?.value;

const columns = ref([
  { field: "last_name", title: "Last Name", value: getFilter("last_name") },
  { field: "first_name", title: "First Name", value: getFilter("first_name") },
  { field: "middle_name", title: "Middle Name", value: getFilter("middle_name") },
  {
    field: "date_of_birth_with_age",
    title: "Age",
    filter: false,
  },
  { field: "phone", title: "Phone", value: getFilter("phone") },
  { field: "gender", title: "Gender", filter: false },
]);

/* DataTable: Fetch Updated Data upon Filtering or Page Change */
const updateRows = debounce((event) => {
  const column_filters = JSON.stringify(event.column_filters);
  router.get(
    route("patients.index"),
    { page: event.current_page, column_filters },
    {
      preserveScroll: true,
      preserveState: true,
    }
  );
}, 500);

/* DataTable: Update Data on Table Change */
watch(
  () => props.patients,
  (newData) => {
    patients.value = newData;
  }
);

defineOptions({ layout: AdminLayout });
</script>
<template>
  <AdminContentWrapper>
    <h1>Patients List</h1>
    <DataTable
      class="mt-2.5 p-[1rem]"
      :search="props.search"
      :hasSearch="true"
      :rows="patients.data"
      :columns="columns"
      :total-rows="patients.total"
      :current-page="currentPage"
      :page-size="patients.per_page"
      :column-filter="true"
      :page-change-fn="updateRows"
    >
      <template #last_name="{ data }">
        <Link class="hover:underline text-blue-600" :href="route('patients.show', data.value.id)">{{
          data.value.last_name
        }}</Link>
      </template>
    </DataTable>
  </AdminContentWrapper>
</template>
