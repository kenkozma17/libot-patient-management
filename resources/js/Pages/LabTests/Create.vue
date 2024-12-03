<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import AddLabTestForm from "@/Pages/LabTests/Partials/AddLabTestForm.vue";
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  categories: Array,
});

defineOptions({ layout: AdminLayout  });
</script>
<template>
  <AdminContentWrapper>
    <AddLabTestForm :categories="categories" />
  </AdminContentWrapper>
</template>
