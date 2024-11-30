<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import UpdateInventoryCategoryForm from "@/Pages/InventoryCategory/Partials/UpdateInventoryCategoryForm.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineOptions({ layout: AdminLayout });
const props = defineProps({
  item: Object,
});
</script>
<template>
  <AdminContentWrapper>
    <UpdateInventoryCategoryForm :item="item" />
  </AdminContentWrapper>
</template>
