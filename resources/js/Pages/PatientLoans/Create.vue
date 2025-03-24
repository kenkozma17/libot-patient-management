<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import AddPatientLoanForm from "@/Pages/PatientLoans/Partials/AddPatientLoanForm.vue";
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    patient: Object,
});

defineOptions({ layout: AdminLayout  });
</script>
<template>
  <AdminContentWrapper>
    <AddPatientLoanForm :patient="patient" />
  </AdminContentWrapper>
</template>
