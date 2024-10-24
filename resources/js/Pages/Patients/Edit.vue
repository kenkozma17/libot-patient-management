<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import UpdatePatientForm from "@/Pages/Patients/Partials/UpdatePatientForm.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineOptions({ layout: AdminLayout });
const props = defineProps({
  patient: Object,
});
</script>
<template>
  <AdminContentWrapper>
    <UpdatePatientForm :patient="patient" />
  </AdminContentWrapper>
</template>
