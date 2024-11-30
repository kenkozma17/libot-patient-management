<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import { ref } from "vue";
import DataTable from "@/Components/Data/DataTable.vue";
import { Link } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";

const props = defineProps({
  categories: Object,
});

let dete = props.categories.data;

const columns = ref([{ field: "name", title: "Category Name" }]);

const rows = ref(props.categories.data);

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
    <DataTable class="mt-2.5 p-[1rem]" :rows="rows" :columns="columns">
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
