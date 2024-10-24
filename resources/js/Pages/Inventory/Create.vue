<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import AddInventoryForm from "@/Pages/Inventory/Partials/AddInventoryForm.vue";
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout  });
</script>
<template>
  <AdminContentWrapper>
    <AddInventoryForm />
  </AdminContentWrapper>
</template>
