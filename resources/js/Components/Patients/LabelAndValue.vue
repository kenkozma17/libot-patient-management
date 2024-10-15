<script setup>
    const props = defineProps({
        label: String,
        value: String,
    });
</script>
<template>
  <div class="flex flex-col">
    <span class="text-xs font-semibold uppercase text-gray-500">{{label}}</span>
    <span class="text-sm">{{ value }} </span>
  </div>
</template>
