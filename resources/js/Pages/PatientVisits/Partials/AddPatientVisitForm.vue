<script setup>
import { useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import InputError from "@/Components/Forms/InputError.vue";
import TwoColumnWrapper from "@/Components/Forms/TwoColumnWrapper.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import Loader from "@/Components/Forms/Loader.vue";
import {useToast} from 'vue-toast-notification';

const $toast = useToast({ position: 'top-right'});

const form = useForm({
  first_name: "",
  last_name: "",
});

const addPatient = () => {
  form.post(route("patients.store"), {
    errorBag: "addPatient",
    preserveScroll: true,
    onSuccess: () => {
        $toast.success('Patient Created Successfully!');
        form.reset();
    },
  });
};
</script>
<template>
  <div>
    <h1>Add New Patient Transaction Form</h1>
    <form
      @submit.prevent="addPatient"
      class="bg-white rounded-md mt-2.5 mb-[6rem] md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <h2 class="font-semibold md:mb-4 mb-2">Personal Information</h2>

      <!-- First and Last Name -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="first_name" value="First Name" />
          <TextInput autofocus v-model="form.first_name" placeholder="John" />
          <InputError :message="form.errors.first_name" class="mt-1.5" />
        </template>
        <template v-slot:col2>
          <InputLabel for="last_name" value="Last Name" />
          <TextInput v-model="form.last_name" placeholder="Doe" />
          <InputError :message="form.errors.last_name" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>
      <div
        class="flex items-center gap-x-2 justify-end md:ml-[16.5rem] bg-white md:px-10 px-8 md:py-8 py-6 fixed bottom-0 left-0 right-0 border-t-2 border-t-dark-gray"
      >
        <Loader v-if="form.processing" />
        <PrimaryButton :class="{ 'opacity-25': form.processing }">
          <span v-if="!form.processing">Save</span>
          <span v-else>Saving...</span>
        </PrimaryButton>
      </div>
    </form>
  </div>
</template>
