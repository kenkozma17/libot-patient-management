<script setup>
import LabelAndValue from "@/Components/Patients/LabelAndValue.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { useToast } from "vue-toast-notification";
import InventoryTransactionForm from "./InventoryTransactionForm.vue";

const $toast = useToast({ position: "top-right" });

const props = defineProps({
  item: Object,
});

const showForm = ref(false);

const toggleTransactionForm = () => (showForm.value = !showForm.value);

const form = useForm({});
const deleteItem = () => {
  if (confirm("Are you sure you want to delete?")) {
    form.delete(route("inventory.destroy", props.item.id), {
      errorBag: "deleteItem",
      preserveScroll: true,
      onSuccess: () => $toast.success("Item Deleted Successfully!"),
    });
  }
};
</script>
<template>
  <div>
    <h1>Inventory</h1>
    <!-- Inventory Item -->
    <div
      class="bg-white rounded-md mt-2.5 md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <div class="flex md:flex-row flex-col md:gap-x-4 gap-x-3">
        <div class="w-full">
          <div class="flex justify-between items-center">
            <h2 class="leading-none md:text-[1.5rem] text-[1.2rem]">
              {{ item.name }}
            </h2>
            <div>
              <PrimaryButton size="small"
                ><Link :href="route('inventory.edit', item.id)"
                  >Edit Item</Link
                ></PrimaryButton
              >
              <PrimaryButton @click="deleteItem" size="small" class="ml-2" color="red"
                >Delete</PrimaryButton
              >
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Inventory Transactions -->
    <div
      class="bg-white rounded-md md:mt-6 md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <div class="md:flex md:flex-row flex-col md:justify-between md:items-center">
        <h2 class="leading-none md:text-[1.5rem] text-[1.2rem]">Transactions</h2>
        <PrimaryButton
          v-if="!showForm"
          class="md:mt-0 mt-2"
          size="small"
          @click="toggleTransactionForm"
          >New Transaction
        </PrimaryButton>
        <a href="#" @click="toggleTransactionForm" v-else>Cancel</a>
      </div>
      <div class="border-t-black border-t md:mt-[2rem] mt-[1rem]" v-if="showForm">
        <InventoryTransactionForm :item="item" />
      </div>
    </div>
  </div>
</template>
