<script setup>
import { useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import InputError from "@/Components/Forms/InputError.vue";
import TwoColumnWrapper from "@/Components/Forms/TwoColumnWrapper.vue";
import SelectInput from "@/Components/Forms/SelectInput.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import Loader from "@/Components/Forms/Loader.vue";
import ButtonWrapper from "@/Components/Forms/ButtonWrapper.vue";
import { useToast } from "vue-toast-notification";
const $toast = useToast({ position: "top-right" });

const form = useForm({
  name: "",
  date_received: "",
  expiration_date: "",
  lot_number: "",
  quantity: "",
  date_opened: "",
});

const addItem = () => {
  form.post(route("inventory.store"), {
    errorBag: "addItem",
    preserveScroll: true,
    onSuccess: () => {
      $toast.success("Item Created Successfully!");
      form.reset();
    },
  });
};
</script>
<template>
  <div>
    <h1>Add New Inventory Item Form</h1>
    <form
      @submit.prevent="addItem"
      class="bg-white rounded-md mt-2.5 mb-[6rem] md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <h2 class="font-semibold md:mb-4 mb-2">Item Details</h2>

      <!-- Name and Date Received -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="name" value="Name" />
          <TextInput autofocus v-model="form.name" placeholder="Glucose" />
          <InputError :message="form.errors.name" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <ButtonWrapper>
        <Loader v-if="form.processing" />
        <PrimaryButton :class="{ 'opacity-25': form.processing }">
          <span v-if="!form.processing">Save</span>
          <span v-else>Saving...</span>
        </PrimaryButton>
      </ButtonWrapper>
    </form>
  </div>
</template>
