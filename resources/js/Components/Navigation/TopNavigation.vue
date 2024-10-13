<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import HamburgerMenu from "@/Components/Navigation/HambugerMenu.vue";
</script>
<template>
  <nav
    class="max-w-[1800px] mx-auto w-full bg-white flex justify-between items-center md:px-10 px-8 py-6 border-b-2 border-dark-gray"
  >
    <div
      class="bg-light-gray inline-block px-[1.8rem] py-[.35rem] text-sm text-dark-gray rounded-sm"
    >
      Tue, 6 Apr 2022
    </div>
    <div>
      <HamburgerMenu @click="$emit('toggleSidePanel')" class="md:hidden block" />
    </div>
  </nav>
</template>
