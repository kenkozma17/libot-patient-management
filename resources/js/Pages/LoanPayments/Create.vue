<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import AddLoanPaymentForm from "@/Pages/LoanPayments/Partials/AddLoanPaymentForm.vue";
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    loan: Object,
});

defineOptions({ layout: AdminLayout  });
</script>
<template>
  <AdminContentWrapper>
    <AddLoanPaymentForm :loan="loan" />
  </AdminContentWrapper>
</template>
