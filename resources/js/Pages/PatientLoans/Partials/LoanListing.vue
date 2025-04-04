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
  payments: Object,
});

/* DataTable Data: Transactions Definitions (For DataTable) */
const payments = ref(props.payments);

const urlParams = new URLSearchParams(window.location.search);
const currentPage = Number(urlParams.get("page")) || 1;

/* DataTable: Field Columns */
const columns = ref([
  { field: "id", title: "ID", filter: false },
  { field: "amount", title: "Payment Amount", filter: false },
  { field: "remaining_balance", title: "Remaining Balance", filter: false },
  { field: "or_number", title: "OR Number", filter: false },
  { field: "payment_date", title: "Payment Date", filter: false },
]);

/* DataTable: Fetch Updated Data upon Filtering or Page Change */
const updateRows = debounce((event) => {
  const column_filters = JSON.stringify(event.column_filters);
  router.get(
    route("patient-loans.show", props.loan.id),
    { page: event.current_page, column_filters },
    {
      preserveScroll: true,
      preserveState: true,
    }
  );
}, 500);

const deleteLoan = () => {
  if (confirm("Are you sure you want to delete this loan?")) {
    const form = useForm({});
    form.delete(route("patient-loans.destroy", props.loan.id), {
      errorBag: "deletePatientLoan",
      preserveScroll: true,
      onSuccess: () => {
        $toast.success("Patient Loan Deleted Successfully!");
        form.reset();
      },
    });
  }
};

const generateLoanReport = () => {
  window.location.href = route("reports.generate-loan-report", props.loan.id);
};

/* DataTable: Update Transactions on Table Change */
watch(
  () => props.payments,
  (newData) => {
    payments.value = newData;
  }
);
</script>
<template>
  <div>
    <h1>Loan Details</h1>
    <div
      class="bg-white rounded-md mt-2.5 md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <div class="flex md:flex-row flex-col md:gap-x-4 gap-x-3">
        <div class="w-full">
          <TitleAndButtonsWrapper>
            <div class="flex flex-wrap items-center gap-[.5rem]">
              <h2 class="leading-none md:text-[1.5rem] text-[1.2rem] md:mt-0 mt-[.5rem]">
                <Link
                  :href="route('patients.show', loan.patient.id)"
                  class="text-blue-600 hover:underline"
                  >{{ loan.patient.full_name }}</Link
                >
              </h2>
              <span
                class="text-xs font-semibold rounded-md px-[.5rem] py-[.25rem] bg-green-300 inline-block"
              >
                Remaining Balance: P{{ Number(loan.remaining_balance).toLocaleString() }}
              </span>
            </div>
            <div>
              <PrimaryButton size="small" color="red" @click="deleteLoan"
                >Delete</PrimaryButton
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
            <LabelAndValue
              label="Monthly Payment"
              :value="'P' + loan.monthly_payment.toLocaleString()"
            />
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
          <div class="flex justify-between">
            <h2 class="leading-none md:text-[1.5rem] text-[1.2rem] md:mt-0 mt-[.5rem]">
              Loan Deductions and Summary
            </h2>
            <PrimaryButton size="small" color="green" @click="generateLoanReport"
              >Export</PrimaryButton
            >
          </div>
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
      <TitleAndButtonsWrapper>
        <h2 class="leading-none md:text-[1.5rem] text-[1.2rem]">Loan Payments</h2>
        <Link :href="route('loan-payments.create', props.loan.id)"
          ><PrimaryButton size="small">New Loan Payment</PrimaryButton>
        </Link>
      </TitleAndButtonsWrapper>
      <div class="flex md:flex-row flex-col md:gap-x-4 gap-x-3">
        <div class="w-full">
          <DataTable
            class="mt-2.5"
            :rows="payments.data"
            :columns="columns"
            :total-rows="payments.total"
            :current-page="currentPage"
            :page-size="payments.per_page"
            :column-filter="true"
            :page-change-fn="updateRows"
          >
            <template #id="{ data }">
              <Link
                :href="route('loan-payments.show', data.value.id)"
                class="text-blue-600 cursor-pointer hover:underline"
                >{{ data.value.id }}</Link
              >
            </template>
            <template #amount="{ data }">
              <span>P{{ Number(data.value.amount).toLocaleString() }}</span>
            </template>
            <template #remaining_balance="{ data }">
              <span>P{{ Number(data.value.remaining_balance).toLocaleString() }}</span>
            </template>
            <template #payment_date="{ data }">
              <span>{{ dayjs(data.value.payment_date).format("YYYY-MM-DD") }}</span>
            </template></DataTable
          >
        </div>
      </div>
    </div>
  </div>
</template>
