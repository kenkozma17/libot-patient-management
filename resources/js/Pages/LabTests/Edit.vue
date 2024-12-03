<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import UpdateLabTestForm from "@/Pages/LabTests/Partials/UpdateLabTestForm.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineOptions({ layout: AdminLayout });
const props = defineProps({
  test: Object,
  categories: Array,
});
</script>
<template>
  <AdminContentWrapper>
    <UpdateLabTestForm :test="test" :categories="categories" />
  </AdminContentWrapper>
</template>
