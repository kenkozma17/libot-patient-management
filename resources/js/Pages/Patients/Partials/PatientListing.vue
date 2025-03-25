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
  patient: Object,
  visits: Object,
  loans: Object,
});

/* DataTable Data: Transactions Definitions (For DataTable) */
const visits = ref(props.visits);
const loans = ref(props.loans);

/* DataTable: Pagination and Filters */
const urlParams = new URLSearchParams(window.location.search);
const currentLoansPage = ref(loans.value.current_page);
const currentVisitsPage = ref(visits.value.current_page);
const filters = reactive(JSON.parse(urlParams.get("column_filters")) || []);

/* DataTable Filtering: Return Filter Value by ID */
const getFilter = (id) => filters?.find((filter) => filter.field === id)?.value;

const columns = ref([
  { field: "patient.full_name", title: "Patient", filter: false },
  { field: "diagnosis", title: "Diagnosis", value: getFilter("diagnosis") },
  {
    field: "requesting_physician",
    title: "Requesting Physician",
    value: getFilter("requesting_physician"),
  },
  { field: "patient_type", title: "Type", value: getFilter("patient_type") },
  { field: "patient_status", title: "Status", value: getFilter("patient_status") },
  { field: "visit_date", title: "Visit Date", value: getFilter("visit_date") },
]);

const loanColumns = ref([
  { field: "id", title: "ID", filter: false },
  { field: "amount_formatted", title: "Amount", filter: false },
  { field: "interest_rate", title: "Interest Rate", filter: false },
  { field: "duration_months", title: "Duration (Months)", filter: false },
  { field: "capital_build_up", title: "CBU", filter: false },
  { field: "start_date", title: "Start Date", filter: false },
  { field: "end_date", title: "End Date", filter: false },
  { field: "check_no", title: "Check No.", filter: false },
  { field: "status", title: "Status", filter: false },
]);

const deletePatient = () => {
  if (confirm("Are you sure you want to delete?")) {
    const form = useForm({});
    form.delete(route("patients.destroy", props.patient.id), {
      errorBag: "deletePatient",
      preserveScroll: true,
      onSuccess: () => $toast.success("Patient Deleted Successfully!"),
    });
  }
};

/* DataTable: Fetch Updated Data upon Filtering or Page Change */
const updateRows = debounce((event, type) => {
  const column_filters = JSON.stringify(event.column_filters);

  let queryObj = {};
  if (type === "loans") {
    queryObj = {
      loans_page: event.current_page,
      visits_page: currentVisitsPage.value,
      column_filters: JSON.stringify(filters),
    };
  } else {
    queryObj = {
      visits_page: event.current_page,
      loans_page: currentLoansPage.value,
      column_filters,
    };
  }

  router.get(route("patients.show", props.patient.id), queryObj, {
    preserveScroll: true,
    preserveState: true,
  });
}, 500);

/* DataTable: Update Data on Table Change */
watch(
  () => [props.visits, props.loans],
  ([newVisits, newLoans]) => {
    visits.value = newVisits;
    loans.value = newLoans;
    currentLoansPage.value = newLoans?.current_page ?? 1;
    currentVisitsPage.value = newVisits?.current_page ?? 1;
  }
);
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
          <TitleAndButtonsWrapper>
            <h2 class="leading-none md:text-[1.5rem] text-[1.2rem] md:mt-0 mt-[.5rem]">
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
          </TitleAndButtonsWrapper>
          <div
            class="grid lg:grid-cols-3 md:grid-cols-2 md:gap-x-6 md:gap-y-4 gap-3 md:mt-4 mt-6"
          >
            <LabelAndValue
              label="Date of Birth"
              v-if="patient && patient.date_of_birth_with_age"
              :value="patient.date_of_birth_with_age"
            />

            <LabelAndValue label="Gender" :value="patient.gender" />

            <LabelAndValue label="Address" :value="patient.address" />

            <LabelAndValue label="Phone" :value="patient.phone" />

            <LabelAndValue label="Email" :value="patient.email ?? 'N/A'" />

            <LabelAndValue
              label="Member/Staff?"
              :value="patient.is_member ? 'Yes' : 'No'"
            />

            <LabelAndValue
              v-if="patient.is_member"
              label="Staff/Member Annual Credits Balance"
              :value="patient.credits_formatted"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Patient Transactions -->
    <div
      class="bg-white rounded-md md:mt-6 mt-3 md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <TitleAndButtonsWrapper>
        <h2 class="leading-none md:text-[1.5rem] text-[1.2rem]">Patient Transactions</h2>
        <Link :href="route('patient-visits.create', { id: props.patient.id })"
          ><PrimaryButton size="small">New Transaction</PrimaryButton>
        </Link>
      </TitleAndButtonsWrapper>

      <DataTable
        class="mt-2.5"
        :rows="visits.data"
        :columns="columns"
        :total-rows="visits.total"
        :current-page="currentVisitsPage"
        :page-size="visits.per_page"
        :column-filter="true"
        :page-change-fn="(e) => updateRows(e, 'visits')"
      >
        <template #patient.full_name="{ data }">
          <Link
            class="hover:underline"
            :href="route('patient-visits.show', data.value.id)"
            >{{ data.value.patient.full_name }}</Link
          >
        </template>
        <template #visit_date="{ data }">
          <span>{{ dayjs(data.value.visit_date).format("YYYY-MM-DD") }}</span>
        </template>
      </DataTable>
    </div>

    <!-- Patient Loans -->
    <div v-if="patient.is_member"
      class="bg-white rounded-md md:mt-6 mt-3 md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <TitleAndButtonsWrapper>
        <h2 class="leading-none md:text-[1.5rem] text-[1.2rem]">Patient Loans</h2>
        <Link :href="route('patient-loans.create', props.patient.id)"
          ><PrimaryButton size="small">New Loan</PrimaryButton>
        </Link>
      </TitleAndButtonsWrapper>

      <DataTable
        class="mt-2.5"
        :rows="loans.data"
        :columns="loanColumns"
        :total-rows="loans.total"
        :current-page="currentLoansPage"
        :page-size="loans.per_page"
        :column-filter="true"
        :page-change-fn="(e) => updateRows(e, 'loans')"
      >
        <template #id="{ data }">
          <Link :href="route('patient-loans.show', data.value.id)" class="text-blue-600 cursor-pointer hover:underline">{{
            data.value.id
          }}</Link>
        </template>
        <template #start_date="{ data }">
          <span>{{ dayjs(data.value.start_date).format("YYYY-MM-DD") }}</span>
        </template>
        <template #end_date="{ data }">
          <span>{{ dayjs(data.value.end_date).format("YYYY-MM-DD") }}</span>
        </template>
        <template #capital_build_up="{ data }">
          <span>{{ data.value.capital_build_up }}%</span>
        </template>
        <template #interest_rate="{ data }">
          <span>{{ data.value.interest_rate }}%</span>
        </template>
      </DataTable>
    </div>
  </div>
</template>
