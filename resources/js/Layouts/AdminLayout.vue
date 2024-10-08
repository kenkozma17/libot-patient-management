<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import SidePanel from '@/Components/Navigation/SidePanel.vue';
import TopNavigation from '@/Components/Navigation/TopNavigation.vue';

defineProps({
    title: String
});
</script>
<template>
    <div>
        <Head :title="title" />

        <SidePanel></SidePanel>

        <div class="content ml-[16.5rem]">
            <TopNavigation />
            <slot></slot>
        </div>
    </div>
</template>
<style lang="scss">
html, body {
    height: 100%;
    background: #F0F0F0;
}
</style>
