<script setup>
import LabelAndValue from "@/Components/Patients/LabelAndValue.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toast-notification";
import TitleAndButtonsWrapper from "@/Components/Partials/TitleAndButtonsWrapper.vue";
import Loader from "@/Components/Forms/Loader.vue";
import Close from "@/Components/Icons/Close.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import { ref } from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const $toast = useToast({ position: "top-right" });

const props = defineProps({
  visit: Object,
  invoiceItems: Array,
  invoice: Object,
});

const uploadFiles = (e) => {
  if (form.files.length > 0) {
    form.post(route("patient-visits.upload-results", props.visit.id), {
      errorBag: "addFile",
      preserveScroll: true,
      onSuccess: () => {
        $toast.success("File/s Uploaded Successfully!");
        form.reset();
      },
    });
  }
};

const deleteFile = (fileId) => {
  const deleteForm = useForm({});
  if (confirm("Are you sure you want to delete this file?")) {
    deleteForm.delete(route("patient-visits.destroy-file", fileId), {
      errorBag: "deleteFile",
      preserveScroll: true,
      onSuccess: () => $toast.success("File Deleted Successfully!"),
    });
  }
};

const deleteLabTest = (patientVisitLabTestId, invoiceItemId) => {
  const deleteLabTestForm = useForm({
    invoice_item_id: invoiceItemId,
    patient_visit_id: props.visit.id,
    invoice_id: props.invoice.id,
  });
  if (confirm("Are you sure you want to delete this lab test?")) {
    deleteLabTestForm.delete(route("patient-visits.destroy-lab-test", patientVisitLabTestId), {
      errorBag: "deleteLabTest",
      preserveScroll: true,
      onSuccess: () => $toast.success("Lab Test Removed Successfully!"),
    });
  }
};

// Patient Visit Form
const isEditMode = ref(false);
const invoiceForm = useForm({
  invoice_id: props.invoice.id,
  patient_visit_id: props.visit.id,
  discount_percentage: props.invoice.discount_percentage,
  or_number: props.invoice.or_number,
  is_paid: props.invoice.is_paid ? true : false,
});

const updateinvoiceForm = () => {
  invoiceForm.put(route("invoices.update", props.invoice.id), {
    errorBag: "updateInvoice",
    preserveScroll: true,
    onSuccess: () => {
      $toast.success("Invoice Details Updated Successfully!");
    },
    onFinish: () => {
      isEditMode.value = false;
    },
  });
};

const form = useForm({
  files: [],
});
</script>
<template>
  <div>
    <!-- Patient Visit Details -->
    <div
      class="bg-white rounded-md mt-2.5 md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <div class="w-full">
        <TitleAndButtonsWrapper>
          <h2 class="leading-none md:text-[1.5rem] text-[1.2rem] md:mt-0 mt-[.5rem]">
            {{ visit.patient.full_name }}
          </h2>
          <div class="relative overflow-hidden inline-block flex gap-[1rem]">
            <Loader v-if="form.processing" />
            <PrimaryButton size="small" type="button">
              <span v-if="form.processing">Uploading</span>
              <span v-else>Upload Lab Results</span>
            </PrimaryButton>
            <input
              @input="form.files = $event.target.files"
              @change="uploadFiles"
              accept=".pdf,.jpg,.docx,.doc,.png"
              type="file"
              multiple
              class="absolute top-0 left-0 opacity-0 cursor-pointer"
            />
          </div>
        </TitleAndButtonsWrapper>
        <div
          class="grid lg:grid-cols-3 md:grid-cols-2 md:gap-x-6 md:gap-y-4 gap-3 md:mt-4 mt-6"
        >
          <LabelAndValue label="Patient Status" :value="visit.patient_status" />

          <LabelAndValue label="Patient Type" :value="visit.patient_type" />

          <LabelAndValue label="Diagnosis" :value="visit.diagnosis ?? 'N/A'" />

          <LabelAndValue label="Visit Date" :value="visit.visit_date_formatted" />

          <LabelAndValue
            label="Requesting Physician"
            :value="visit.requesting_physician ?? 'N/A'"
          />

          <LabelAndValue label="Paid" :value="invoice.is_paid ? 'YES' : 'NO'" />

          <div class="flex flex-col" v-if="visit.results.length">
            <span class="text-xs font-semibold uppercase text-gray-500">Lab Results</span>
            <div v-for="result in visit.results" class="flex justify-between">
              <a
                :href="'/' + result.result_path"
                :key="result.result_path"
                target="_blank"
                class="text-xs text-blue-600 hover:underline inline-block"
                >{{ result.result_name }}
              </a>
              <Close class="cursor-pointer" @click="deleteFile(result.id)" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Patient Visit Consumables -->
    <div
      class="bg-white rounded-md md:mt-6 mt-3 md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <TitleAndButtonsWrapper>
        <h2 class="leading-none md:text-[1.5rem] text-[1.2rem]">Lab Tests Consumed</h2>
      </TitleAndButtonsWrapper>

      <!-- Lab Tests -->
      <ul class="md:mt-[1rem] mt-[.7rem] flex flex-col gap-[1rem]">
        <li
          class="shadow-lg bg-white rounded-md border-2 border-blue-100 md:py-[1rem] md: py-[.75rem] md:px-[1.5rem] px-[1rem]"
          v-for="(visitItem, key) in invoiceItems"
        >
          <div class="flex justify-between">
            <div>
              <span class="font-medium">#{{ visitItem.invoice_id }}</span> -
              <span class="font-bold">{{ visitItem.lab_test.name }}</span>
            </div>
            <div>
              <PrimaryButton size="small" type="button" color="red" @click="deleteLabTest(visitItem.patient_visit_lab_test_id, visitItem.invoice_item_id)"
                >Delete</PrimaryButton
              >
            </div>
          </div>

          <!-- Inventory Items -->
          <ul
            class="border-b border-light-gray mt-[.5rem] pb-[.5rem] pl-[1rem] md:pl-[.75rem] mb-[.75rem]"
          >
            <li
              v-for="item in invoiceItems[key].inventory_items"
              class="text-xs font-semibold uppercase text-gray-500 mb-1"
            >
              {{ item.pivot.quantity }} x {{ item.name }}
            </li>
          </ul>

          <!-- Pricing -->
          <div class="flex md:flex-row flex-col gap-[1rem] items-start justify-between">
            <div class="flex items-start justify-center gap-[.5rem]">
              <Checkbox checked="" @change="" />
              <InputLabel for="consumed" value="Consumed?" />
            </div>
            <div class="md:w-[200px] w-full flex justify-between">
              <span class="text-xs font-semibold uppercase text-gray-500">Price</span>
              <span class="text-xs font-semibold uppercase text-gray-500">{{
                visitItem.unit_price
              }}</span>
            </div>
            <!-- <div
              class="md:w-[200px] w-full flex justify-between"
              v-if="visitItem.discount_percentage > 0"
            >
              <span class="text-xs font-semibold uppercase text-gray-500"
                >Discount ({{ visitItem.discount_percentage }}%)</span
              >
              <span class="text-xs font-semibold uppercase text-gray-500">
                {{ visitItem.discount_amount }}</span
              >
            </div> -->
          </div>
        </li>
      </ul>

      <!-- Transaction Summary -->
      <form
        @submit.prevent="updateinvoiceForm"
        class="md:mt-[1rem] mt-[.7rem] lg:w-1/2 w-full ml-auto shadow-lg bg-white rounded-md border-2 border-blue-100 md:py-[1rem] md: py-[.75rem] md:px-[1.5rem] px-[1rem]"
      >
        <p class="font-bold">Transaction Summary</p>
        <ul class="flex flex-col gap-[.75rem] md:mt-[1rem]">
          <li>
            <InputLabel for="total_amount" value="Total Amount" />
            <TextInput :value="'P' + invoice.total_amount" disabled />
          </li>
          <li>
            <InputLabel for="discount_percentage" value="Senior/PWD/Disc (%)" />
            <TextInput
              type="number"
              min="0"
              max="100"
              v-model="invoiceForm.discount_percentage"
              disabled
            />
          </li>
          <li>
            <InputLabel for="discount_amount" value="Discount Amount" />
            <TextInput :value="'P' + invoice.discount_amount" disabled />
          </li>
          <li>
            <InputLabel for="total_amount_due" value="Total Amount Due" />
            <TextInput :value="'P' + invoice.total_amount_due" disabled />
          </li>
          <li>
            <InputLabel for="or_number" value="OR No." required />
            <TextInput v-model="invoiceForm.or_number" :disabled="!isEditMode" />
          </li>
          <li>
            <InputLabel for="is_paid" value="Has this been paid for?" />
            <Checkbox
              :disabled="!isEditMode"
              :checked="invoiceForm.is_paid"
              @change="invoiceForm.is_paid = !invoiceForm.is_paid"
            />
          </li>
        </ul>
        <div class="mt-[1rem] flex gap-[.5rem]">
          <SecondaryButton
            size="small"
            type="button"
            v-if="isEditMode"
            @click="isEditMode = false"
            >Cancel</SecondaryButton
          >
          <PrimaryButton
            size="small"
            type="button"
            @click="isEditMode = true"
            v-if="!isEditMode"
          >
            <span> Edit </span>
          </PrimaryButton>
          <PrimaryButton
            :class="{ 'opacity-25': invoiceForm.processing }"
            size="small"
            v-if="isEditMode"
          >
            <span v-if="!invoiceForm.processing"> Save </span>
            <span v-else> Saving... </span>
          </PrimaryButton>
        </div>
      </form>
    </div>
  </div>
</template>
