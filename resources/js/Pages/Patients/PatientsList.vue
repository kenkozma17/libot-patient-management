<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AdminContentWrapper from '@/Components/Partials/AdminContentWrapper.vue';
import AddPatientForm from '@/Pages/Patients/Partials/AddPatientForm.vue';
</script>
<template>
    <AdminLayout title="Patients List">
        <AdminContentWrapper>
            <AddPatientForm />
        </AdminContentWrapper>
    </AdminLayout>
</template>
