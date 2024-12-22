<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import { ref } from "vue";
import DataTable from "@/Components/Data/DataTable.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
  visits: Object,
  search: String,
});

const columns = ref([
  { field: "patient.full_name", title: "Patient" },
  { field: "diagnosis", title: "Diagnosis" },
  { field: "requesting_physician", title: "Requesting Physician" },
  { field: "patient_type", title: "Type" },
  { field: "patient_status", title: "Status" },
  { field: "visit_date_formatted", title: "Visit Date" },
]);

const rows = ref(props.visits.data);

defineOptions({ layout: AdminLayout });
</script>
<template>
  <AdminContentWrapper>
    <h1>Patient Transactions List</h1>
    <DataTable
      class="mt-2.5 p-[1rem]"
      :search="props.search"
      :hasSearch="true"
      :rows="rows"
      :columns="columns"
    >
      <template #patient.full_name="{ data }">
        <Link class="hover:underline" :href="route('patients.show', data.value.patient.id)">{{
          data.value.patient.full_name
        }}</Link>
      </template>
    </DataTable>
  </AdminContentWrapper>
</template>
