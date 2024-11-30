<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import InventoryTransactionListing from "@/Pages/InventoryTransaction/Partials/InventoryTransactionListing.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
  transaction: Object,
});

defineOptions({ layout: AdminLayout });
</script>
<template>
  <AdminContentWrapper>
    <InventoryTransactionListing :transaction="transaction" />
  </AdminContentWrapper>
</template>
