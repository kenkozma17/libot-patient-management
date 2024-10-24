<script setup>
import { computed } from "vue";
const props = defineProps({
  type: {
    type: String,
    default: "submit",
  },
  size: {
    type: String,
    default: "medium",
  },
  color: {
    type: String,
    default: 'green'
  }
});

const sizeClasses = computed(() => {
  if (props.size === "small") {
    return "px-5 py-2";
  }
  return "px-14 py-3";
});

const colorClasses = computed(() => {
    if(props.color === "red") {
        return 'bg-dark-red hover:bg-dark-red/[0.8] focus:bg-dark-red/[0.9] active:bg-dark-red/[0.9]'
    }
    return 'bg-dark-green hover:bg-dark-green/[0.8] focus:bg-dark-green/[0.9] active:bg-dark-green/[0.9]'
});
</script>

<template>
  <button
    :type="type"
    :class="sizeClasses + ' ' + colorClasses"
    class="inline-flex items-center bg-dark-green border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-dark-green/[0.8] focus:bg-dark-green/[0.9] active:bg-dark-green/[0.9] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
  >
    <slot />
  </button>
</template>
