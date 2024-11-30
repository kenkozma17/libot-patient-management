<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import { ref } from "vue";
import DataTable from "@/Components/Data/DataTable.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
  patients: Object,
  search: String,
});

const columns = ref([
  { field: "full_name", title: "Patient Name" },
  { field: "date_of_birth_with_age", title: "Age" },
  { field: "phone", title: "Phone" },
  { field: "gender", title: "Gender" },
]);

const rows = ref(props.patients.data);

defineOptions({ layout: AdminLayout });
</script>
<template>
  <AdminContentWrapper>
    <h1>Patients List</h1>
    <DataTable
      class="mt-2.5 p-[1rem]"
      :search="props.search"
      :hasSearch="true"
      :rows="rows"
      :columns="columns"
    >
      <template #full_name="{ data }">
        <Link class="hover:underline" :href="route('patients.show', data.value.id)">{{
          data.value.full_name
        }}</Link>
      </template>
    </DataTable>
  </AdminContentWrapper>
</template>
