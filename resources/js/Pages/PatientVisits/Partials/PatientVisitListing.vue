<script setup>
import LabelAndValue from "@/Components/Patients/LabelAndValue.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toast-notification";
import TitleAndButtonsWrapper from "@/Components/Partials/TitleAndButtonsWrapper.vue";
import Loader from "@/Components/Forms/Loader.vue";
import Close from "@/Components/Icons/Close.vue";

const $toast = useToast({ position: "top-right" });

const props = defineProps({
  visit: Object,
  invoiceItems: Array,
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
          <span class="font-medium">#{{ visitItem.invoice_id }}</span> -
          <span class="font-bold">{{ visitItem.lab_test.name }}</span>

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
          <div class="flex flex-col items-end">
            <div class="md:w-[200px] w-full flex justify-between">
              <span class="text-xs font-semibold uppercase text-gray-500">Price</span>
              <span class="text-xs font-semibold uppercase text-gray-500">{{
                visitItem.unit_price
              }}</span>
            </div>
            <div
              class="md:w-[200px] w-full flex justify-between"
              v-if="visitItem.discount_percentage > 0"
            >
              <span class="text-xs font-semibold uppercase text-gray-500"
                >Discount ({{ visitItem.discount_percentage }}%)</span
              >
              <span class="text-xs font-semibold uppercase text-gray-500">
                {{ visitItem.discount_amount }}</span
              >
            </div>
            <div
              class="md:w-[200px] w-full flex justify-between border-t border-light-gray pt-2 mt-2"
            >
              <span class="text-xs font-semibold uppercase text-gray-500">Sub Total</span>
              <span class="text-xs font-semibold uppercase text-gray-500">{{
                visitItem.total_price
              }}</span>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
