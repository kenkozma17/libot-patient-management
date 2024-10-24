<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import { ref } from "vue";
import DataTable from "@/Components/Data/DataTable.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
  inventory_items: Object,
  search: String,
});

const columns = ref([
  { field: "name", title: "Item Name" },
]);

const rows = ref(props.inventory_items.data);

defineOptions({ layout: AdminLayout });
</script>
<template>
  <AdminContentWrapper>
    <h1>Inventory List</h1>
    <DataTable
      class="mt-2.5"
      :search="props.search"
      :hasSearch="true"
      :rows="rows"
      :columns="columns"
    >
      <template #name="{ data }">
        <Link class="hover:underline" :href="route('inventory.show', data.value.id)">{{
          data.value.name
        }}</Link>
      </template>
    </DataTable>
  </AdminContentWrapper>
</template>
