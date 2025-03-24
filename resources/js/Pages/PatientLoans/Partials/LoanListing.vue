<script setup>
import Profile from "@/Components/Icons/Profile.vue";
import LabelAndValue from "@/Components/Patients/LabelAndValue.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import { Link, useForm, router } from "@inertiajs/vue3";
import { useToast } from "vue-toast-notification";
import TitleAndButtonsWrapper from "@/Components/Partials/TitleAndButtonsWrapper.vue";
import DataTable from "@/Components/Data/DataTable.vue";
import { ref, watch, reactive } from "vue";
import { debounce } from "lodash";
import dayjs from "dayjs";

const $toast = useToast({ position: "top-right" });

const props = defineProps({
  loan: Object,
});
</script>
<template>
  <div>
    <h1>Loan Details</h1>
    <!-- Loan Details -->
    <div
      class="bg-white rounded-md mt-2.5 md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <div class="flex md:flex-row flex-col md:gap-x-4 gap-x-3">
        <div class="w-full">
          <TitleAndButtonsWrapper>
            <h2 class="leading-none md:text-[1.5rem] text-[1.2rem] md:mt-0 mt-[.5rem]">
              Loanee:
              <Link
                :href="route('patients.show', loan.patient.id)"
                class="text-blue-600 hover:underline"
                >{{ loan.patient.full_name }}</Link
              >
            </h2>
            <div>
              <PrimaryButton size="small"
                ><Link :href="route('patient-loans.edit', loan.id)"
                  >Edit Loan</Link
                ></PrimaryButton
              >
            </div>
          </TitleAndButtonsWrapper>
          <div
            class="grid lg:grid-cols-3 md:grid-cols-2 md:gap-x-6 md:gap-y-4 gap-3 md:mt-4 mt-6"
          >
            <LabelAndValue label="Principal Loan Amount" :value="loan.amount_formatted" />
            <LabelAndValue label="Interest Rate" :value="loan.interest_rate + '%'" />
            <LabelAndValue
              label="Service Fee"
              :value="'P' + loan.service_fee.toLocaleString()"
            />
            <LabelAndValue label="Purpose" :value="loan.purpose ?? 'N/A'" />
            <LabelAndValue label="Duration" :value="loan.duration_months + ' months'" />
            <LabelAndValue
              v-if="loan.capital_build_up > 0"
              label="Capital Build Up"
              :value="loan.capital_build_up + '%'"
            />
            <LabelAndValue
              label="Start Date"
              :value="dayjs(loan.start_date).format('YYYY-MM-DD')"
            />
            <LabelAndValue
              label="End Date"
              :value="dayjs(loan.end_date).format('YYYY-MM-DD')"
            />
            <LabelAndValue label="Status" :value="loan.status" />
          </div>
        </div>
      </div>
    </div>

    <!-- Loan Deductions -->
    <div
      class="bg-white rounded-md mt-2.5 md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <div class="flex md:flex-row flex-col md:gap-x-4 gap-x-3">
        <div class="w-full">
          <h2 class="leading-none md:text-[1.5rem] text-[1.2rem] md:mt-0 mt-[.5rem]">
            Loan Deductions and Summary
          </h2>
          <div class="flex flex-col md:gap-x-6 md:gap-y-4 gap-3 md:mt-4 mt-6">
            <LabelAndValue label="Principal Loan Amount" :value="loan.amount_formatted" />
            <LabelAndValue
              label="Service Fee"
              class="text-red-600"
              :value="'P' + loan.service_fee.toLocaleString()"
            />
            <LabelAndValue
              :label="
                'Total Interest Amount (' +
                loan.interest_rate +
                '% x ' +
                loan.duration_months +
                ' months)'
              "
              class="text-red-600"
              :value="'P' + loan.total_interest_amount.toLocaleString()"
            />

            <LabelAndValue
              :label="'Capital Build Up (' + loan.capital_build_up + '%)'"
              class="text-red-600"
              :value="'P' + loan.cpu_amount.toLocaleString()"
            />

            <LabelAndValue
              :label="'Total Deductions'"
              class="text-red-600"
              :value="'P' + loan.total_deductions.toLocaleString()"
            />

            <LabelAndValue
              :label="'Net Amount Due'"
              class="font-semibold"
              :value="'P' + Number(loan.net_amount_released).toLocaleString()"
            />

            <LabelAndValue
              :label="'Date Released'"
              :value="dayjs(loan.date_released).format('YYYY-MM-DD')"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Loan Payments -->
    <div
      class="bg-white rounded-md mt-2.5 md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <div class="flex md:flex-row flex-col md:gap-x-4 gap-x-3">
        <div class="w-full">
          <h2 class="leading-none md:text-[1.5rem] text-[1.2rem] md:mt-0 mt-[.5rem]">
            Loan Payments
          </h2>
        </div>
      </div>
    </div>
  </div>
</template>
