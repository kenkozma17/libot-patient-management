<script setup>
import { useForm, Link } from "@inertiajs/vue3";
import { reactive } from "vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import InputError from "@/Components/Forms/InputError.vue";
import TwoColumnWrapper from "@/Components/Forms/TwoColumnWrapper.vue";
import SelectInput from "@/Components/Forms/SelectInput.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import Loader from "@/Components/Forms/Loader.vue";
import ButtonWrapper from "@/Components/Forms/ButtonWrapper.vue";
import regions from "@/regions";
import provinces from "@/provinces";
import { useToast } from "vue-toast-notification";

const regionsList = reactive(regions);
const provincesList = reactive(provinces);
const $toast = useToast({ position: "top-right" });

const props = defineProps({
  patient: Object,
});

const form = useForm({
  first_name: props.patient.first_name,
  last_name: props.patient.last_name,
  middle_name: props.patient.middle_name,
  gender: props.patient.gender,
  birthdate: props.patient.birthdate,
  street_address: props.patient.street_address,
  region: props.patient.region,
  province: props.patient.province,
  municipality: props.patient.municipality,
  barangay: props.patient.barangay,
  postal_code: props.patient.postal_code,
  country: "Philippines",
  phone: props.patient.phone,
  email: props.patient.email,
});

const addPatient = () => {
  form.put(route("patients.update", props.patient.id), {
    errorBag: "updatePatient",
    preserveScroll: true,
    onSuccess: () => {
      $toast.success("Patient Updated Successfully!");
      form.reset();
    },
  });
};
</script>
<template>
  <div>
    <h1>Edit Patient Form</h1>
    <span>{{ props.patient.full_name }}</span>
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

      <!-- Middle Name and Gender -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="middle_name" value="Middle Name" />
          <TextInput v-model="form.middle_name" placeholder="Carson" />
          <InputError :message="form.errors.middle_name" class="mt-1.5" />
        </template>
        <template v-slot:col2>
          <InputLabel for="gender" value="Gender" />
          <SelectInput v-model="form.gender">
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </SelectInput>
          <InputError :message="form.errors.gender" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <!-- Birthdate -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="birthdate" value="Birthdate" />
          <TextInput type="date" v-model="form.birthdate" placeholder="Birthdate" />
          <InputError :message="form.errors.birthdate" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <h2 class="font-semibold md:mb-4 mb-2 mt-8">Address</h2>

      <!-- Street Address and Region -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="street_address" value="Street Address" />
          <TextInput v-model="form.street_address" placeholder="930 Arcilla St." />
          <InputError :message="form.errors.street_address" class="mt-1.5" />
        </template>
        <template v-slot:col2>
          <InputLabel for="region" value="Region" />
          <SelectInput v-model="form.region">
            <option value="">Select Region</option>
            <option v-for="region in regionsList" :key="region.id" :value="region.name">
              {{ region.name }}
            </option>
          </SelectInput>
          <InputError :message="form.errors.region" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <!-- Province and Municipality -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="province" value="Province" />
          <SelectInput v-model="form.province">
            <option value="">Select Province</option>
            <option value="CATANDUANES">CATANDUANES</option>
            <option
              v-for="province in provincesList"
              :key="province.id"
              :value="province.name"
            >
              {{ province.name }}
            </option>
          </SelectInput>
          <InputError :message="form.errors.province" class="mt-1.5" />
        </template>
        <template v-slot:col2>
          <InputLabel for="municipality" value="City/Municipality" />
          <TextInput v-model="form.municipality" placeholder="Virac" />
          <InputError :message="form.errors.municipality" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <!-- Barangay and Postal Code -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="barangay" value="Barangay" />
          <TextInput v-model="form.barangay" placeholder="Francia (POB)" />
          <InputError :message="form.errors.barangay" class="mt-1.5" />
        </template>
        <template v-slot:col2>
          <InputLabel for="postal_code" value="Postal Code" />
          <TextInput v-model="form.postal_code" placeholder="4800" />
          <InputError :message="form.errors.postal_code" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <h2 class="font-semibold md:mb-4 mb-2 mt-8">Contact Information</h2>

      <!-- Phone and Email -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="phone" value="Phone Number" />
          <TextInput v-model="form.phone" placeholder="09692394333" />
          <InputError :message="form.errors.phone" class="mt-1.5" />
        </template>
        <template v-slot:col2>
          <InputLabel for="email" value="Email Address" />
          <TextInput v-model="form.email" placeholder="john@gmail.com" />
          <InputError :message="form.errors.email" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <ButtonWrapper>
        <Loader v-if="form.processing" />
        <Link
          v-if="!form.processing"
          :href="route('patients.show', props.patient.id)"
          class="mr-4"
        >
          Cancel
        </Link>
        <PrimaryButton :class="{ 'opacity-25': form.processing }">
          <span v-if="!form.processing">Update</span>
          <span v-else>Updating...</span>
        </PrimaryButton>
      </ButtonWrapper>
    </form>
  </div>
</template>
