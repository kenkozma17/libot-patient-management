<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import PatientVisitListing from "@/Pages/PatientVisits/Partials/PatientVisitListing.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineOptions({ layout: AdminLayout });
const props = defineProps({
  visit: Object,
  invoiceItems: Array,
  invoice: Object,
});
</script>
<template>
  <AdminContentWrapper>
    <h1>Patient Transaction</h1>
    <PatientVisitListing :visit="visit" :invoice-items="invoiceItems" :invoice="invoice" />
  </AdminContentWrapper>
</template>
