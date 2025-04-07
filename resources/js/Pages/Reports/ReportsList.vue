<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import SelectInput from "@/Components/Forms/SelectInput.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import dayjs from "dayjs";
import { useToast } from "vue-toast-notification";

const $toast = useToast({ position: "top-right" });


const props = defineProps({
  invoicesPerMonth: Array,
});

const months = ref([
  "January",
  "February",
  "March",
  "April",
  "May",
  "June",
  "July",
  "August",
  "September",
  "October",
  "November",
  "December",
]);

const yearsArray = [];
for (let year = 2024; year <= dayjs().year(); year++) {
  yearsArray.push(year);
}

const years = ref(yearsArray);

const form = useForm({
  month: "",
  year: dayjs().year(),
  date: "",
});

const generateReport = (reportUrl) => {
  const params = new URLSearchParams({
    month: form.month,
    year: form.year,
    date: form.date,
  });

//   form.get(route(reportUrl), {
//     errorBag: "generateReport",
//     preserveScroll: true,
//     onSuccess: () => {
//       $toast.success("Report Generated Successfully!");
//       form.reset();
//     },
//     onError: (err) => {
//         $toast.error(err.message);
//     }
//   });
    window.location.href = route(reportUrl) + "?" + params.toString();
};

defineOptions({ layout: AdminLayout });
</script>
<template>
  <AdminContentWrapper>
    <h1>Reports</h1>
    <div class="bg-white rounded-md shadow-md w-full mt-2.5 p-[1rem]">
      <h2>Monthly Income Reports</h2>
      <div
        class="bg-white shadow-md p-[1rem] mt-2.5 border border-gray-200 overflow-y-scroll"
        style="max-height: 300px"
      >
        <ul>
          <li class="text-sm">1. Monthly Cash Flow Report</li>
        </ul>
      </div>

      <form
        @submit.prevent="generateReport('reports.generate')"
        class="mt-4 flex lg:flex-row flex-col gap-[.5em]"
      >
        <SelectInput v-model="form.month" required class="lg:w-[10rem] w-full">
          <option value="">Select Month</option>
          <option :value="key + 1" v-for="(month, key) in months" :key="month">
            {{ month }}
          </option>
        </SelectInput>
        <SelectInput required class="lg:w-[10rem] w-full">
          <option value="">Select Year</option>
          <option :value="year" v-for="year in years" :key="year">{{ year }}</option>
        </SelectInput>
        <PrimaryButton>
          <span>Generate</span>
        </PrimaryButton>
      </form>
    </div>
    <div class="bg-white rounded-md shadow-md w-full mt-2.5 p-[1rem]">
      <h2>Daily Reports</h2>
      <div
        class="bg-white shadow-md p-[1rem] mt-2.5 border border-gray-200 overflow-y-scroll"
        style="max-height: 300px"
      >
        <ul>
          <li class="text-sm">1. Daily Cash Collection Report</li>
        </ul>
      </div>

      <form
        @submit.prevent="generateReport('reports.generate-daily-collection')"
        class="mt-4 flex lg:flex-row flex-col gap-[.5em]"
      >
        <TextInput type="date" v-model="form.date" required class="lg:w-[10rem] w-full" />
        <PrimaryButton>
          <span>Generate</span>
        </PrimaryButton>
      </form>
    </div>
  </AdminContentWrapper>
</template>
