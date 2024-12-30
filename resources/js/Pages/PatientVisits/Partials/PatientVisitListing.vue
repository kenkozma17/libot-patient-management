<script setup>
import LabelAndValue from "@/Components/Patients/LabelAndValue.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toast-notification";
import TitleAndButtonsWrapper from "@/Components/Partials/TitleAndButtonsWrapper.vue";
import DataTable from "@/Components/Data/DataTable.vue";
import { ref } from "vue";

const $toast = useToast({ position: "top-right" });

const props = defineProps({
  visit: Object,
  invoiceItems: Array,
});

// const columns = ref([
//   { field: "patient.full_name", title: "Patient" },
//   { field: "diagnosis", title: "Diagnosis" },
//   { field: "requesting_physician", title: "Requesting Physician" },
//   { field: "patient_type", title: "Type" },
//   { field: "patient_status", title: "Status" },
//   { field: "visit_date_formatted", title: "Visit Date" },
// ]);

// const rows = ref(props.visits.data);

const form = useForm({});
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
            <div class="md:w-[200px] w-full flex justify-between border-t border-light-gray pt-2 mt-2">
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
