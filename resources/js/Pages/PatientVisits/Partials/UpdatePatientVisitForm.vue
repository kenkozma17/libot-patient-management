<script setup>
import { useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/Forms/TextInput.vue";
import TextArea from "@/Components/Forms/TextArea.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import InputError from "@/Components/Forms/InputError.vue";
import TwoColumnWrapper from "@/Components/Forms/TwoColumnWrapper.vue";
import SelectInput from "@/Components/Forms/SelectInput.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import Loader from "@/Components/Forms/Loader.vue";
import { useToast } from "vue-toast-notification";

const $toast = useToast({ position: "top-right" });

const props = defineProps({
  visit: Object,
});

const form = useForm({
  patient_id: props.visit.patient_id,
  requesting_physician: props.visit.requesting_physician,
  diagnosis: props.visit.diagnosis,
  visit_date: props.visit.visit_date_no_time,
  patient_age: String(props.visit.patient_age),
  patient_type: props.visit.patient_type,
  patient_status: props.visit.patient_status,
  office_type: props.visit.office_type,
});

const updatePatientVisit = () => {
  form.put(route("patient-visits.update", props.visit.id), {
    errorBag: "updatePatientVisit",
    preserveScroll: true,
    onSuccess: () => {
      $toast.success("Patient Transaction Updated Successfully!");
      form.reset();
    },
  });
};
</script>
<template>
  <div>
    <h1>Update Patient Transaction Form</h1>
    <form
      @submit.prevent="updatePatientVisit"
      class="bg-white rounded-md mt-2.5 mb-[6rem] md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <h2 class="font-semibold md:mb-4 mb-2">Personal Information</h2>

      <!-- First and Last Name -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="first_name" value="First Name" />
          <TextInput v-model="props.visit.patient.first_name" disabled="true" />
        </template>
        <template v-slot:col2>
          <InputLabel for="last_name" value="Last Name" />
          <TextInput v-model="props.visit.patient.last_name" disabled="true" />
        </template>
      </TwoColumnWrapper>

      <!-- Middle Name and Age -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="middle_name" value="Middle Name" />
          <TextInput v-model="props.visit.patient.middle_name" disabled="true" />
        </template>
        <template v-slot:col2>
          <InputLabel for="patient_age" value="Age" />
          <TextInput
            v-model="form.patient_age"
            placeholder="Patient Age"
            disabled="true"
          />
        </template>
      </TwoColumnWrapper>

      <h2 class="font-semibold md:mb-4 mb-2 md:mt-8 mt-2">Visit Details</h2>

      <!-- Type and Visit Date -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="patient_type" value="Type" />
          <SelectInput v-model="form.patient_type">
            <option value="">Select Type</option>
            <option value="Send Out">Send Out</option>
            <option value="Walk In">Walk In</option>
          </SelectInput>
          <InputError :message="form.errors.patient_type" class="mt-1.5" />
        </template>
        <template v-slot:col2>
          <InputLabel for="patient_status" value="Status" />
          <SelectInput v-model="form.patient_status">
            <option value="">Select Status</option>
            <option value="On Process">On Process</option>
            <option value="Done">Done</option>
            <option value="Patient Received">Patient Received</option>
            <option value="Not Yet Done">Not Yet Done</option>
          </SelectInput>
          <InputError :message="form.errors.patient_status" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <!-- Requesting Physician and Visit Date -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="requesting_physician" value="Requesting Physician" />
          <TextInput v-model="form.requesting_physician" placeholder="" />
          <InputError :message="form.errors.requesting_physician" class="mt-1.5" />
        </template>
        <template v-slot:col2>
          <InputLabel for="visit_date" value="Visit Date" />
          <TextInput type="date" v-model="form.visit_date" placeholder="Visit Date" />
          <InputError :message="form.errors.visit_date" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <!-- Diagnosis -->
      <TwoColumnWrapper>
        <template v-slot:col2>
          <InputLabel for="diagnosis" value="Diagnosis" />
          <TextArea v-model="form.diagnosis" placeholder="" />
          <InputError :message="form.errors.diagnosis" class="mt-1.5" />
        </template>
        <template v-slot:col1>
          <InputLabel for="offce_type" value="Office Type" />
          <TextInput v-model="form.office_type" />
          <InputError :message="form.errors.office_type" class="mt-1.5" />
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
<style scoped>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type="number"] {
  -moz-appearance: textfield;
}
</style>
