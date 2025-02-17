<script setup>
import { useForm, Link } from "@inertiajs/vue3";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import InputError from "@/Components/Forms/InputError.vue";
import TwoColumnWrapper from "@/Components/Forms/TwoColumnWrapper.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import Loader from "@/Components/Forms/Loader.vue";
import ButtonWrapper from "@/Components/Forms/ButtonWrapper.vue";
import { useToast } from "vue-toast-notification";
import { computed } from "vue";
const $toast = useToast({ position: "top-right" });

const props = defineProps({
  item: Object,
  transactionType: String,
});

const isIncrease = computed(() => {
  if (props.transactionType === "INCREASE") {
    return true;
  }
  return false;
});

const form = useForm({
  inventory_item_id: props.item.id,
  date_received: props.item.date_received,
  expiration_date: props.item.expiration_date,
  lot_number: props.item.lot_number,
  quantity: props.item.quantity,
  date_opened: props.item.date_opened,
  transaction_type: props.transactionType,
  notes: props.item.notes,
});

const AddTransaction = () => {
  form.post(route("inventory-transactions.store"), {
    errorBag: "AddTransaction",
    preserveScroll: true,
    onSuccess: () => {
      $toast.success("Inventory Transaction Created Successfully!");
      form.reset();
    },
  });
};
</script>
<template>
  <div>
    <!-- <h2>Inventory Transaction Form</h2> -->
    <form
      @submit.prevent="AddTransaction"
      class="bg-white rounded-md mt-2.5 md:py-[1.4rem] py-[1.125rem]"
    >
      <h2 class="font-semibold md:mb-4 mb-2">Inventory Details (<span :class="{'text-red-600' : transactionType === 'DECREASE', 'text-green-600' : transactionType === 'INCREASE'}">{{ transactionType }}</span>)</h2>

      <template v-if="isIncrease">
        <!-- Lot Number and Quantity -->
        <TwoColumnWrapper>
          <template v-slot:col1>
            <InputLabel for="lot_number" value="Lot Number" />
            <TextInput autofocus v-model="form.lot_number" placeholder="12345" />
            <InputError :message="form.errors.lot_number" class="mt-1.5" />
          </template>
          <template v-slot:col2>
            <InputLabel for="quantity" value="Quantity" />
            <TextInput type="number" v-model="form.quantity" placeholder="12" />
            <InputError :message="form.errors.quantity" class="mt-1.5" />
          </template>
        </TwoColumnWrapper>

        <!-- Lot Number and Expiration Date -->
        <TwoColumnWrapper>
          <template v-slot:col1>
            <InputLabel for="date_received" value="Date Received" />
            <TextInput autofocus type="date" v-model="form.date_received" />
            <InputError :message="form.errors.date_received" class="mt-1.5" />
          </template>
          <template v-slot:col2>
            <InputLabel for="expiration_date" value="Expiration Date" />
            <TextInput type="date" v-model="form.expiration_date" />
            <InputError :message="form.errors.expiration_date" class="mt-1.5" />
          </template>
        </TwoColumnWrapper>

        <!-- Date Opened and Notes -->
        <TwoColumnWrapper>
          <template v-slot:col1>
            <InputLabel for="date_opened" value="Date Opened" />
            <TextInput autofocus type="date" v-model="form.date_opened" />
            <InputError :message="form.errors.date_opened" class="mt-1.5" />
          </template>
          <template v-slot:col2>
            <InputLabel for="notes" value="Notes" />
            <TextInput v-model="form.notes" />
            <InputError :message="form.errors.notes" class="mt-1.5" />
          </template>
        </TwoColumnWrapper>
      </template>
      <template v-if="isIncrease === false">
        <!-- Quantity and Notes -->
        <TwoColumnWrapper>
          <template v-slot:col1>
            <InputLabel for="quantity" value="Quantity" />
            <TextInput type="number" v-model="form.quantity" placeholder="12" />
            <InputError :message="form.errors.quantity" class="mt-1.5" />
          </template>
          <template v-slot:col2>
            <InputLabel for="notes" value="Notes" />
            <TextInput v-model="form.notes" />
            <InputError :message="form.errors.notes" class="mt-1.5" />
          </template>
        </TwoColumnWrapper>
      </template>

      <div class="w-full flex justify-end">
        <PrimaryButton :class="{ 'opacity-25': form.processing }">
          <span v-if="!form.processing">Save</span>
          <span v-else>Save...</span>
        </PrimaryButton>
      </div>
    </form>
  </div>
</template>
