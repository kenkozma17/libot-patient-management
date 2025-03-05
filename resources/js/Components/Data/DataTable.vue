<script setup>
import Vue3Datatable from "@bhplugin/vue3-datatable";
import { useSlots, ref } from "vue";
import TextInput from "@/Components/Forms/TextInput.vue";

const slots = useSlots();
const props = defineProps({
  rows: Array,
  columns: Array,
  hasSearch: {
    type: Boolean,
    default: false,
  },
  search: String,
  columnFilter: {
    type: Boolean,
    default: false,
  },
  totalRows: {
    type: Number,
    default: 0,
  },
  pageChangeFn: {
    type: Function,
    default: () => {}
  },
  currentPage: {
    type: Number,
    default: 1,
  },
  pageSize: {
    type: Number,
    default: 1,
  }
});

const s = ref(props.search);
</script>
<template>
  <div class="bg-white rounded-md">
    <form v-if="hasSearch" class="md:mb-4 mb-3 md:w-1/2 w-full">
      <TextInput name="search" placeholder="Search" v-model="s" />
    </form>
    <vue3-datatable
      :rows="rows"
      :columns="columns"
      :columnFilter="columnFilter"
      :totalRows="totalRows"
      :page="currentPage"
      @change="pageChangeFn"
      :isServerMode="true"
      :pageSize="pageSize"
    >
      <template v-for="(slotFn, slotName) in slots" v-slot:[slotName]="data">
        <slot :name="slotName" :data="data"></slot>
      </template>
    </vue3-datatable>
  </div>
</template>
<style>
.bh-pagesize {
  padding-right: 2.5rem !important;
}
</style>
