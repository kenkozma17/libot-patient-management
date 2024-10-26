<script setup>
import Profile from "@/Components/Icons/Profile.vue";
import LabelAndValue from "@/Components/Patients/LabelAndValue.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toast-notification";

const $toast = useToast({ position: "top-right" });

const props = defineProps({
  patient: Object,
});

const form = useForm({});

const deletePatient = () => {
  if (confirm("Are you sure you want to delete?")) {
    form.delete(route("patients.destroy", props.patient.id), {
      errorBag: "deletePatient",
      preserveScroll: true,
      onSuccess: () => $toast.success("Patient Deleted Successfully!"),
    });
  }
};
</script>
<template>
  <div>
    <!-- Patient Profile -->
    <div
      class="bg-white rounded-md mt-2.5 md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <div class="flex md:flex-row flex-col md:gap-x-4 gap-x-3">
        <div>
          <div class="bg-gray-100 inline-block md:w-auto w-full p-4">
            <Profile class="md:w-[5rem] w-1/2 md:mx-0 mx-auto" />
          </div>
        </div>
        <div class="w-full">
          <div class="flex justify-between items-center">
            <h2 class="leading-none md:text-[1.5rem] text-[1.2rem]">
              {{ patient.full_name }}
            </h2>
            <div>
              <PrimaryButton size="small"
                ><Link :href="route('patients.edit', patient.id)"
                  >Edit Profile</Link
                ></PrimaryButton
              >
              <PrimaryButton @click="deletePatient" size="small" class="ml-2" color="red"
                >Delete</PrimaryButton
              >
            </div>
          </div>
          <div
            class="grid lg:grid-cols-3 md:grid-cols-2 md:gap-x-6 md:gap-y-4 gap-3 md:mt-4 mt-6"
          >
            <LabelAndValue
              label="Date of Birth"
              :value="patient.date_of_birth_with_age"
            />

            <LabelAndValue label="Gender" :value="patient.gender" />

            <LabelAndValue label="Address" :value="patient.address" />

            <LabelAndValue label="Phone" :value="patient.phone" />

            <LabelAndValue label="Email" :value="patient.email ?? 'N/A'" />
          </div>
        </div>
      </div>
    </div>

    <!-- Patient Transactions -->
    <div
      class="bg-white rounded-md md:mt-6 md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <div class="flex justify-between items-center">
        <h2 class="leading-none md:text-[1.5rem] text-[1.2rem]">Patient Transactions</h2>
        <Link :href="route('patient-visits.create', { id: 1 })"
          ><PrimaryButton size="small">New Transaction</PrimaryButton>
        </Link>
      </div>
    </div>
  </div>
</template>
