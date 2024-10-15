<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import AddPatientVisitForm from "@/Pages/PatientVisits/Partials/AddPatientVisitForm.vue";
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout  });
</script>
<template>
  <AdminContentWrapper>
    <AddPatientVisitForm />
  </AdminContentWrapper>
</template>
