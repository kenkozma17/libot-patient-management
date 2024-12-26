<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import { ref } from "vue";
import DataTable from "@/Components/Data/DataTable.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
  lab_tests: Object,
  search: String,
});

const columns = ref([
  { field: "name", title: "Item Name" },
  { field: "category", title: "Category" },
  { field: "price", title: "Regular Price" },
//   { field: "senior_price", title: "Senior/PWD Price" },
]);

const rows = ref(props.lab_tests.data);

defineOptions({ layout: AdminLayout });
</script>
<template>
  <AdminContentWrapper>
    <h1>Lab Tests List</h1>
    <DataTable
      class="mt-2.5 p-[1rem]"
      :search="props.search"
      :hasSearch="true"
      :rows="rows"
      :columns="columns"
    >
      <template #name="{ data }">
        <Link class="hover:underline" :href="route('lab-tests.edit', data.value.id)">{{
          data.value.name
        }}</Link>
      </template>
      <template #category="{ data }">
        <span>{{ data.value.category.name }}</span>
      </template>
    </DataTable>
  </AdminContentWrapper>
</template>
