<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import LotTransactionsListing from "@/Pages/Lot/Partials/LotTransactionsListing.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineOptions({ layout: AdminLayout });
const props = defineProps({
  lotTransactions: Object,
  primaryTransaction: Object,
});
</script>
<template>
  <AdminContentWrapper>
    <LotTransactionsListing :primary-transaction="primaryTransaction" :lot-transactions="lotTransactions" />
  </AdminContentWrapper>
</template>
