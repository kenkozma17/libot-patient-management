<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import InventoryItemListing from "@/Pages/Inventory/Partials/InventoryItemListing.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineOptions({ layout: AdminLayout });
const props = defineProps({
  item: Object,
  transactions: Object,
  itemLotNumbers: Array,
});
</script>
<template>
  <AdminContentWrapper>
    <InventoryItemListing :transactions="transactions" :item="item" :item-lot-numbers="itemLotNumbers" />
  </AdminContentWrapper>
</template>
