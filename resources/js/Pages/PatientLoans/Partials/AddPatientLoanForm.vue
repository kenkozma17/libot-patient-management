<script setup>
import { useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/Forms/TextInput.vue";
import TextArea from "@/Components/Forms/TextArea.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import InputError from "@/Components/Forms/InputError.vue";
import TwoColumnWrapper from "@/Components/Forms/TwoColumnWrapper.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import Loader from "@/Components/Forms/Loader.vue";
import { useToast } from "vue-toast-notification";
import { computed } from "vue";

const $toast = useToast({ position: "top-right" });

const props = defineProps({
  patient: Object,
});

const form = useForm({
  patient_id: props.patient.id,
  amount: "",
  purpose: "",
  start_date: "",
  end_date: "",
  date_released: "",
  net_amount_released: "",
  capital_build_up: "0",
  interest_rate: "0",
  duration_months: "1",
  service_fee: "0",
  check_no: "",
});

const totalInterest = computed(() => {
  if (form.interest_rate && form.amount && form.duration_months) {
    return ((form.interest_rate * form.amount) / 100) * form.duration_months;
  }
  return 0;
});

const totalCbu = computed(() => {
  if (form.capital_build_up) {
    return (form.amount * form.capital_build_up) / 100;
  }
  return 0;
});

const totalDeductions = computed(() => {
  if (totalInterest.value || form.service_fee || totalCbu.value) {
    return (
      Number(totalInterest.value) + Number(form.service_fee) + Number(totalCbu.value)
    );
  }
});

const totalDue = computed(() => {
  if (totalDeductions.value) {
    return form.amount - totalDeductions.value;
  }
});

const addPatientLoan = () => {
  form.net_amount_released = totalDue.value;
  form.post(route("patient-loans.store"), {
    errorBag: "addPatientLoan",
    preserveScroll: true,
    onSuccess: () => {
      $toast.success("Patient Loan Created Successfully!");
      form.reset();
    },
  });
};
</script>
<template>
  <div>
    <h1>Add New Patient Loan Form</h1>
    <form
      @submit.prevent="addPatientLoan"
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

      <!-- Middle Name -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="middle_name" value="Middle Name" />
          <TextInput v-model="props.patient.middle_name" disabled="true" />
        </template>
      </TwoColumnWrapper>

      <h2 class="font-semibold md:mb-4 mb-2 md:mt-8 mt-2">Loan Details</h2>

      <!-- Amount and Interest Rate -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="amount" value="Amount (PHP)" />
          <TextInput v-model="form.amount" placeholder="" />
          <InputError :message="form.errors.amount" class="mt-1.5" />
        </template>
        <template v-slot:col2>
          <InputLabel for="interest_rate" value="Interest Rate (%)" />
          <TextInput type="number" v-model="form.interest_rate" placeholder="" />
          <InputError :message="form.errors.interest_rate" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <!-- Purpose and Duration -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="purpose" value="Purpose" />
          <TextInput v-model="form.purpose" placeholder="" />
          <InputError :message="form.errors.purpose" class="mt-1.5" />
        </template>
        <template v-slot:col2>
          <InputLabel for="duration_months" value="Duration (Months)" />
          <TextInput type="number" v-model="form.duration_months" placeholder="" />
          <InputError :message="form.errors.duration_months" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <!-- Start and End Date -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="start_date" value="Start Date" />
          <TextInput type="date" v-model="form.start_date" />
          <InputError :message="form.errors.start_date" class="mt-1.5" />
        </template>
        <template v-slot:col2>
          <InputLabel for="end_date" value="End Date" />
          <TextInput type="date" v-model="form.end_date" />
          <InputError :message="form.errors.end_date" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <!-- Date Released -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="date_released" value="Date Released" />
          <TextInput type="date" v-model="form.date_released" />
          <InputError :message="form.errors.date_released" class="mt-1.5" />
        </template>
        <template v-slot:col2>
          <InputLabel for="check_no" value="Check No." />
          <TextInput v-model="form.check_no" placeholder="" />
          <InputError :message="form.errors.check_no" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <h2 class="font-semibold md:mb-4 mb-2 md:mt-8 mt-2">Loan Deductions</h2>

      <!-- Service Fee and Capital Build Up -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="service_fee" value="Service Fee (PHP)" />
          <TextInput type="number" v-model="form.service_fee" placeholder="" />
          <InputError :message="form.errors.service_fee" class="mt-1.5" />
        </template>
        <template v-slot:col2>
          <InputLabel for="captial_build_up" value="Capital Build Up (%)" />
          <TextInput type="number" v-model="form.capital_build_up" />
          <InputError :message="form.errors.capital_build_up" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <!-- Summary -->
      <div
        class="md:mt-[2rem] mt-[.7rem] lg:w-1/2 w-full ml-auto shadow-lg bg-white rounded-md border-2 border-blue-100 md:py-[1rem] md: py-[.75rem] md:px-[1.5rem] px-[1rem]"
      >
        <p class="font-bold">Loan Summary</p>

        <ul class="flex flex-col gap-[.75rem] md:mt-[1rem]">
          <li>
            <InputLabel for="total_amount" value="Loan Amount" />
            <TextInput
              :value="form.amount ? 'P' + Number(form.amount).toLocaleString() : ''"
              disabled
            />
          </li>
          <li>
            <InputLabel for="service_fee" value="Service Fee" />
            <TextInput
              :value="form.service_fee ? 'P' + form.service_fee.toLocaleString() : ''"
              disabled
            />
          </li>
          <li>
            <InputLabel
              for="capital_build_up"
              :value="'Capital Build Up ' + '(' + form.capital_build_up + '%)'"
            />
            <TextInput
              :value="totalCbu ? 'P' + totalCbu.toLocaleString() : ''"
              disabled
            />
          </li>
          <li>
            <InputLabel
              for="total_interest_amount"
              :value="'Total Interest Amount' + ' (' + form.interest_rate + '%)'"
            />
            <TextInput
              :value="totalInterest ? 'P' + totalInterest.toLocaleString() : ''"
              disabled
            />
          </li>
          <li>
            <InputLabel for="total_deductions" value="Total Deductions" />
            <TextInput
              :value="totalDeductions ? 'P' + totalDeductions.toLocaleString() : ''"
              disabled
            />
          </li>
          <li>
            <InputLabel for="total_due" value="Net Amount Due" />
            <TextInput
              :value="totalDue ? 'P' + totalDue.toLocaleString() : ''"
              disabled
            />
          </li>
        </ul>
      </div>

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
