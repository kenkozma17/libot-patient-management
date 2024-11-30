<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import AddInventoryCategoryForm from "@/Pages/InventoryCategory/Partials/AddInventoryCategoryForm.vue";
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout  });
</script>
<template>
  <AdminContentWrapper>
    <AddInventoryCategoryForm />
  </AdminContentWrapper>
</template>
