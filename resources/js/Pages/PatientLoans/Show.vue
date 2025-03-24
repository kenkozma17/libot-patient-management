<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import LoanListing from "@/Pages/PatientLoans/Partials/LoanListing.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineOptions({ layout: AdminLayout });
const props = defineProps({
  loan: Object,
});
</script>
<template>
  <AdminContentWrapper>
    <LoanListing :loan="loan" />
  </AdminContentWrapper>
</template>
