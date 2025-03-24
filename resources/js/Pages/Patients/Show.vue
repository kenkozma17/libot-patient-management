<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import PatientListing from "@/Pages/Patients/Partials/PatientListing.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineOptions({ layout: AdminLayout });
const props = defineProps({
  patient: Object,
  visits: Object,
  loans: Object,
});
</script>
<template>
  <AdminContentWrapper>
    <PatientListing :patient="patient" :visits="visits" :loans="loans" />
  </AdminContentWrapper>
</template>
