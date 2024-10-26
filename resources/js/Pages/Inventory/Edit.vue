<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import UpdateInventoryForm from "@/Pages/Inventory/Partials/UpdateInventoryForm.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineOptions({ layout: AdminLayout });
const props = defineProps({
  item: Object,
});
</script>
<template>
  <AdminContentWrapper>
    <UpdateInventoryForm :item="item" />
  </AdminContentWrapper>
</template>
