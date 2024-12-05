<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import AddPatientVisitForm from "@/Pages/PatientVisits/Partials/AddPatientVisitForm.vue";
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    patient: Object,
    tests: Array,
    inventory_items: Array,
});

defineOptions({ layout: AdminLayout  });
</script>
<template>
  <AdminContentWrapper>
    <AddPatientVisitForm :patient="patient" :tests="tests" :inventory_items="inventory_items" />
  </AdminContentWrapper>
</template>
