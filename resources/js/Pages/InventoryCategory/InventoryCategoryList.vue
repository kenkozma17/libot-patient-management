<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import { ref, watch } from "vue";
import DataTable from "@/Components/Data/DataTable.vue";
import { Link, router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";

const props = defineProps({
  categories: Object,
});

/* DataTable: Pagination and Filters */
const urlParams = new URLSearchParams(window.location.search);
const currentPage = Number(urlParams.get("page")) || 1;

/* DataTable Data: Transactions Definitions (For DataTable) */
const categories = ref(props.categories);

const columns = ref([{ field: "name", title: "Category Name" }]);

/* DataTable: Fetch Updated Data upon Filtering or Page Change */
const updateRows = (event) => {
  router.get(
    route("inventory-categories.index"),
    { page: event.current_page },
    {
      preserveScroll: true,
      preserveState: true,
    }
  );
};

/* DataTable: Update Transactions on Table Change */
watch(
  () => props.categories,
  (newData) => {
    categories.value = newData;
  }
);

defineOptions({ layout: AdminLayout });
</script>
<template>
  <AdminContentWrapper>
    <div class="flex lg:flex-row flex-col gap-[.5rem] justify-between">
      <h1>Inventory Categories List</h1>
      <PrimaryButton>
        <Link class="w-full" :href="route('inventory-categories.create')"
          >Create Category</Link
        >
      </PrimaryButton>
    </div>
    <DataTable
      class="mt-2.5 p-[1rem]"
      :rows="categories.data"
      :columns="columns"
      :total-rows="categories.total"
      :page-size="categories.per_page"
      :current-page="currentPage"
      :page-change-fn="updateRows"
    >
      <template #name="{ data }">
        <Link
          class="hover:underline"
          :href="route('inventory-categories.edit', data.value.id)"
          >{{ data.value.name }}</Link
        >
      </template>
    </DataTable>
  </AdminContentWrapper>
</template>
