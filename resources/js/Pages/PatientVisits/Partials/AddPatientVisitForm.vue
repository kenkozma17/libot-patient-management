<script setup>
import { useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import InputError from "@/Components/Forms/InputError.vue";
import TwoColumnWrapper from "@/Components/Forms/TwoColumnWrapper.vue";
import SelectInput from "@/Components/Forms/SelectInput.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import Loader from "@/Components/Forms/Loader.vue";
import { useToast } from "vue-toast-notification";

const $toast = useToast({ position: "top-right" });

const props = defineProps({
  patient: Object,
});

const form = useForm({
  patient_id: props.patient.id,
  requesting_physician: "",
  visit_date: "",
  patient_age: String(props.patient.age),
  patient_status: "",
});

const addPatientVisit = () => {
  form.post(route("patient-visits.store"), {
    errorBag: "addPatientVisit",
    preserveScroll: true,
    onSuccess: () => {
      $toast.success("Patient Transaction Created Successfully!");
      form.reset();
    },
  });
};
</script>
<template>
  <div>
    <h1>Add New Patient Transaction Form</h1>
    <form
      @submit.prevent="addPatientVisit"
      class="bg-white rounded-md mt-2.5 mb-[6rem] md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <h2 class="font-semibold md:mb-4 mb-2">Personal Information</h2>

      <!-- First and Last Name -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="first_name" value="First Name" />
          <TextInput v-model="props.patient.first_name" disabled="true" />
        </template>
        <template v-slot:col2>
          <InputLabel for="last_name" value="Last Name" />
          <TextInput v-model="props.patient.last_name" disabled="true" />
        </template>
      </TwoColumnWrapper>

      <!-- Middle Name and Age -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="middle_name" value="Middle Name" />
          <TextInput v-model="props.patient.middle_name" disabled="true" />
        </template>
        <template v-slot:col2>
          <InputLabel for="patient_age" value="Age" />
          <TextInput v-model="form.patient_age" placeholder="Patient Age" disabled="true" />
        </template>
      </TwoColumnWrapper>

      <!-- Status and Visit Date -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="patient_status" value="Status" />
          <SelectInput v-model="form.patient_status">
            <option value="">Select Status</option>
            <option value="send-out">Send Out/EBMC</option>
            <option value="walk-in">Walk In</option>
          </SelectInput>
          <InputError :message="form.errors.patient_status" class="mt-1.5" />
        </template>
        <template v-slot:col2>
          <InputLabel for="visit_date" value="Visit Date" />
          <TextInput type="date" v-model="form.visit_date" placeholder="Visit Date" />
          <InputError :message="form.errors.visit_date" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <!-- Requesting Physician -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="requesting_physician" value="Requesting Physician" />
          <TextInput v-model="form.requesting_physician" placeholder="" />
          <InputError :message="form.errors.requesting_physician" class="mt-1.5" />
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
