<script setup>
import LeftArrow from '@/Components/Icons/LeftArrow.vue'
import RightArrow from '@/Components/Icons/RightArrow.vue'
const props = defineProps({
  currentPage: Number,
  isLoading: Boolean,
  prevPage: String,
  nextPage: String,
  totalPages: Number,
});
</script>
<template>
  <div class="w-full flex items-center gap-[1rem] justify-center">
    <a :href="prevPage"
      :disabled="props.currentPage === 1 || props.isLoading"
    >
      <LeftArrow class="w-4 h-4" />
    </a>
    <div class="font-semibold text-sm">
        Page {{ props.currentPage }} of {{ props.totalPages }}
    </div>
    <a :href="nextPage" :disabled="props.isLoading || props.currentPage === props.totalPages" @click="props.nextPage">
        <RightArrow class="w-4 h-4" />
    </a>
  </div>
</template>
