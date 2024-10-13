<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref } from "vue";
import SidePanel from "@/Components/Navigation/SidePanel.vue";
import TopNavigation from "@/Components/Navigation/TopNavigation.vue";

defineProps({
  title: String,
});

const showSidePanel = ref(false);

const toggleSidePanel = () => {
  showSidePanel.value = !showSidePanel.value;
};
</script>
<template>
  <div>
    <Head :title="title" />

    <SidePanel
      :class="[{ 'side-panel--active': showSidePanel }]"
    ></SidePanel>

    <div class="content md:ml-[16.5rem]">
      <TopNavigation @toggle-side-panel="toggleSidePanel" />
      <slot></slot>
    </div>
  </div>
</template>
<style lang="scss">
html,
body {
  height: 100%;
  background: #f0f0f0;
}

.content {
    transition: 0.35s ease margin;
}
</style>
