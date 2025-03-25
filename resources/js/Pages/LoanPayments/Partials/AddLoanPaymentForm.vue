<script setup>
import { router, useForm, Link } from "@inertiajs/vue3";
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
  loan: Object,
});

const form = useForm({
  patient_loan_id: props.loan.id,
  amount: "",
  payment_date: "",
  remaining_balance: props.loan.remaining_balance,
  or_number: "",
});

const remainingBalance = computed(() => form.remaining_balance - form.amount);

const addLoanPayment = () => {
  form.post(route("loan-payments.store"), {
    errorBag: "addLoanPayment",
    preserveScroll: true,
    onSuccess: () => {
      $toast.success("Loan Payment Created Successfully!");
      form.reset();
      router.visit('patient-loans.show', loan.id);
    },
  });
};
</script>
<template>
  <div>
    <h1>Add New Loan Payment Form</h1>
    <Link :href="route('patient-loans.show', loan.id)" class="text-blue-600">Back</Link>
    <form
      @submit.prevent="addLoanPayment"
      class="bg-white rounded-md mt-2.5 mb-[6rem] md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <h2 class="font-semibold md:mb-4 mb-2">Personal Information</h2>

      <!-- First and Last Name -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="first_name" value="First Name" />
          <TextInput v-model="props.loan.patient.first_name" disabled="true" />
        </template>
        <template v-slot:col2>
          <InputLabel for="last_name" value="Last Name" />
          <TextInput v-model="props.loan.patient.last_name" disabled="true" />
        </template>
      </TwoColumnWrapper>

      <!-- Middle Name -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="middle_name" value="Middle Name" />
          <TextInput v-model="props.loan.patient.middle_name" disabled="true" />
        </template>
      </TwoColumnWrapper>

      <h2 class="font-semibold md:mb-4 mb-2">Payment Details</h2>

      <!-- Amount and Payment Date -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="amount" value="Amount (PHP)" />
          <TextInput
            v-model="form.amount"
            placeholder=""
            type="number"
            :max="loan.remaining_balance"
          />
          <InputError :message="form.errors.amount" class="mt-1.5" />
        </template>
        <template v-slot:col2>
          <InputLabel for="payment_date" value="Payment Date" />
          <TextInput type="date" v-model="form.payment_date" placeholder="" />
          <InputError :message="form.errors.payment_date" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <!-- Remaining Balance OR Number -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="remaining_balance" value="Remaining Balance" />
          <TextInput
            :value="'P' + Number(remainingBalance).toLocaleString()"
            placeholder=""
            disabled
          />
        </template>
        <template v-slot:col2>
          <InputLabel for="or_number" value="OR Number" />
          <TextInput v-model="form.or_number" placeholder="" />
          <InputError :message="form.errors.or_number" class="mt-1.5" />
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
