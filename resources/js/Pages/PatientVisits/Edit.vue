<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import UpdatePatientVisitForm from "@/Pages/PatientVisits/Partials/UpdatePatientVisitForm.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineOptions({ layout: AdminLayout });
const props = defineProps({
  visit: Object,
});
</script>
<template>
  <AdminContentWrapper>
    <UpdatePatientVisitForm :visit="visit" />
  </AdminContentWrapper>
</template>
