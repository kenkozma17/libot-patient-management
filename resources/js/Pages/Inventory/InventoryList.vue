<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import { ref, watch } from "vue";
import DataTable from "@/Components/Data/DataTable.vue";
import { Link, router } from "@inertiajs/vue3";
import { debounce } from 'lodash';

const props = defineProps({
  inventory_items: Object,
  search: String,
});

/* DataTable: Pagination and Filters */
const urlParams = new URLSearchParams(window.location.search);
const currentPage = Number(urlParams.get("page")) || 1;
const filters = JSON.parse(urlParams.get("column_filters"));

/* DataTable Data: Transactions Definitions (For DataTable) */
const inventoryItems = ref(props.inventory_items);

/* DataTable Filtering: Return Filter Value by ID */
const getFilter = (id) => filters?.find((filter) => filter.field === id)?.value;

const columns = ref([
  { field: "name", title: "Item Name", value: getFilter("name") },
  { field: "unit", title: "Unit", value: getFilter("unit") },
  { field: "category", title: "Category", value: getFilter("category") },
  {
    field: "classification",
    title: "Classification",
    value: getFilter("classification"),
  },
  { field: "current_stock", title: "Current Stock", filter: false },
]);

/* DataTable: Fetch Updated Data upon Filtering or Page Change */
const updateRows = debounce((event) => {
  const column_filters = JSON.stringify(event.column_filters);
  router.get(
    route("inventory.index"),
    { page: event.current_page, column_filters },
    {
      preserveScroll: true,
      preserveState: true,
    }
  );
}, 500);

/* DataTable: Update Transactions on Table Change */
watch(
  () => props.inventory_items,
  (newData) => {
    inventoryItems.value = newData;
  }
);

defineOptions({ layout: AdminLayout });
</script>
<template>
  <AdminContentWrapper>
    <h1>Inventory List</h1>
    <DataTable
      class="mt-2.5 p-[1rem]"
      :search="props.search"
      :hasSearch="true"
      :rows="inventoryItems.data"
      :columns="columns"
      :total-rows="inventoryItems.total"
      :page-size="inventoryItems.per_page"
      :current-page="currentPage"
      :column-filter="true"
      :page-change-fn="updateRows"
    >
      <template #name="{ data }">
        <Link class="hover:underline" :href="route('inventory.show', data.value.id)">{{
          data.value.name
        }}</Link>
      </template>
      <template #category="{ data }">
        <span>{{ data.value.category.name }}</span>
      </template>
      <template #classification="{ data }">
        <span>{{ data.value.classification_formatted }}</span>
      </template>
    </DataTable>
  </AdminContentWrapper>
</template>
