<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import AddPatientForm from "@/Pages/Patients/Partials/AddPatientForm.vue";
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout  });
</script>
<template>
  <AdminContentWrapper>
    <AddPatientForm />
  </AdminContentWrapper>
</template>
