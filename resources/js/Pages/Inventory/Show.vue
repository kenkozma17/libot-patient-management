<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import InventoryItemListing from "@/Pages/Inventory/Partials/InventoryItemListing.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineOptions({ layout: AdminLayout });
const props = defineProps({
  item: Object,
});
</script>
<template>
  <AdminContentWrapper>
    <InventoryItemListing :item="item" />
  </AdminContentWrapper>
</template>
