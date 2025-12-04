<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import { ref, watch } from "vue";
import DataTable from "@/Components/Data/DataTable.vue";
import { Link, router } from "@inertiajs/vue3";
import { debounce } from "lodash";

const props = defineProps({
  lab_tests: Object,
  search: String,
});

/* DataTable: Pagination and Filters */
const urlParams = new URLSearchParams(window.location.search);
const currentPage = Number(urlParams.get("page")) || 1;
const filters = JSON.parse(urlParams.get("column_filters"));

/* DataTable Data: Transactions Definitions (For DataTable) */
const labTests = ref(props.lab_tests);

/* DataTable Filtering: Return Filter Value by ID */
const getFilter = (id) => filters?.find((filter) => filter.field === id)?.value;

const columns = ref([
  { field: "name", title: "Item Name", value: getFilter('name') },
  { field: "category", title: "Category", value: getFilter('category') },
  { field: "price", title: "Regular Price", value: getFilter('price') },
]);

/* DataTable: Fetch Updated Data upon Filtering or Page Change */
const updateRows = debounce((event) => {
  const column_filters = JSON.stringify(event.column_filters);
  router.get(
    route("lab-tests.index"),
    { page: event.current_page, column_filters },
    {
      preserveScroll: true,
      preserveState: true,
    }
  );
}, 500);

/* DataTable: Update Data on Table Change */
watch(
  () => props.lab_tests,
  (newData) => {
    labTests.value = newData;
  }
);

defineOptions({ layout: AdminLayout });
</script>
<template>
  <AdminContentWrapper>
    <h1>Lab Tests List</h1>
    <DataTable
      class="mt-2.5 p-[1rem]"
      :search="props.search"
      :hasSearch="true"
      :rows="labTests.data"
      :columns="columns"
      :total-rows="labTests.total"
      :current-page="currentPage"
      :page-size="labTests.per_page"
      :column-filter="true"
      :page-change-fn="updateRows"
    >
      <template #name="{ data }">
        <Link class="hover:underline text-blue-600" :href="route('lab-tests.edit', data.value.id)">{{
          data.value.name
        }}</Link>
      </template>
      <template #category="{ data }">
        <span>{{ data.value.category.name }}</span>
      </template>
    </DataTable>
  </AdminContentWrapper>
</template>
