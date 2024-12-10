<script setup>
import { useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import InputError from "@/Components/Forms/InputError.vue";
import TwoColumnWrapper from "@/Components/Forms/TwoColumnWrapper.vue";
import ThreeColumnWrapper from "@/Components/Forms/ThreeColumnWrapper.vue";
import SelectInput from "@/Components/Forms/SelectInput.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import Loader from "@/Components/Forms/Loader.vue";
import { useToast } from "vue-toast-notification";
import { ref } from "vue";

const $toast = useToast({ position: "top-right" });

const props = defineProps({
  patient: Object,
  tests: Array,
  inventory_items: Array,
});

const form = useForm({
  patient_id: props.patient.id,
  requesting_physician: "",
  visit_date: "",
  patient_age: String(props.patient.age),
  patient_type: "",
  patient_status: "",
  selected_test: "",
  lab_tests: [],
  lab_tests_and_items: [],
});

const addInventoryItem = (labTestIndex) => {
  form.lab_tests_and_items[labTestIndex].items.push({
    id: "",
    quantity: "",
  });
};

const removeInventoryItem = (index, itemIndex) => {
    form.lab_tests_and_items[index].items.splice(itemIndex);
};

const addLabTest = () => {
  form.lab_tests.push(form.selected_test);
  form.lab_tests_and_items.push({
    id: form.selected_test,
    name: props.tests.find((t) => t.id == form.selected_test).name,
    items: [
      {
        id: "",
        quantity: "",
      },
    ],
  });
};

const removeLabTest = (index) => {
  form.lab_tests.splice(index, 1);
  form.lab_tests_and_items.splice(index, 1);
};

const addPatientVisit = () => {
  form.post(route("patient-visits.store"), {
    errorBag: "addPatientVisit",
    preserveScroll: true,
    onSuccess: () => {
      $toast.success("Patient Transaction Created Successfully!");
      form.reset();
    },
  });
};
</script>
<template>
  <div>
    <h1>Add New Patient Transaction Form</h1>
    <form
      @submit.prevent="addPatientVisit"
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

      <!-- Middle Name and Age -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="middle_name" value="Middle Name" />
          <TextInput v-model="props.patient.middle_name" disabled="true" />
        </template>
        <template v-slot:col2>
          <InputLabel for="patient_age" value="Age" />
          <TextInput
            v-model="form.patient_age"
            placeholder="Patient Age"
            disabled="true"
          />
        </template>
      </TwoColumnWrapper>

      <h2 class="font-semibold md:mb-4 mb-2 md:mt-8 mt-2">Visit Details</h2>

      <!-- Type and Visit Date -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="patient_type" value="Type" />
          <SelectInput v-model="form.patient_type">
            <option value="">Select Type</option>
            <option value="Send Out">Send Out</option>
            <option value="Walk In">Walk In</option>
          </SelectInput>
          <InputError :message="form.errors.patient_type" class="mt-1.5" />
        </template>
        <template v-slot:col2>
          <InputLabel for="patient_status" value="Status" />
          <SelectInput v-model="form.patient_status">
            <option value="">Select Status</option>
            <option value="On Process">On Process</option>
            <option value="Done">Done</option>
            <option value="Patient Received">Patient Received</option>
            <option value="Not Yet Done">Not Yet Done</option>
          </SelectInput>
          <InputError :message="form.errors.patient_status" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <!-- Requesting Physician -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="requesting_physician" value="Requesting Physician" />
          <TextInput v-model="form.requesting_physician" placeholder="" />
          <InputError :message="form.errors.requesting_physician" class="mt-1.5" />
        </template>
        <template v-slot:col2>
          <InputLabel for="visit_date" value="Visit Date" />
          <TextInput type="date" v-model="form.visit_date" placeholder="Visit Date" />
          <InputError :message="form.errors.visit_date" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <h2 class="font-semibold md:mb-4 mb-2 md:mt-8 mt-2">Consumables</h2>

      <div class="flex items-end gap-[.5rem]">
        <div class="w-full">
          <InputLabel for="lab_tests" value="Lab Tests" />
          <SelectInput v-model="form.selected_test">
            <option value="">Select Lab Test</option>
            <option v-for="test in props.tests" :key="test.id" :value="test.id">
              {{ test.category.name + " - " + test.name }}
            </option>
          </SelectInput>
          <InputError :message="form.errors.lab_tests" class="mt-1.5" />
        </div>
        <div>
          <PrimaryButton type="button" v-if="form.selected_test" @click="addLabTest">
            Add
          </PrimaryButton>
        </div>
      </div>
      <div v-if="form.lab_tests_and_items.length" class="md:mt-4 mt-2">
        <InputLabel
          for="lab_tests"
          :value="'Selected Lab Tests' + ' (' + form.lab_tests_and_items.length + ')'"
        />
        <ul>
          <li v-for="(test, index) in form.lab_tests_and_items" :key="test.slug">
            <div class="flex justify-between items-end md:mb-[1rem]">
              <span class="w-1/2 font-bold">{{ index + 1 + ". " + test.name }}</span>
              <div class="flex gap-2">
                <PrimaryButton
                  type="button"
                  @click="removeLabTest(index)"
                  class="py-[.25rem] px-2"
                  color="red"
                  >Remove Lab Test</PrimaryButton
                >
                <PrimaryButton
                  type="button"
                  @click="addInventoryItem(index)"
                  class="py-[.25rem] px-2"
                  >Add Item</PrimaryButton
                >
              </div>
            </div>
            <div
              class="md:ml-4 ml-2 grid lg:grid-cols-12 gap-4 items-end"
              v-for="(item, itemIndex) in form.lab_tests_and_items[index].items"
            >
              <div class="col-span-5">
                <InputLabel for="inventory_item" value="Inventory Item" />
                <SelectInput v-model="item.id" required>
                  <option value="">Select Inventory Item</option>
                  <option
                    v-for="item in inventory_items"
                    :key="item.slug"
                    :value="item.id"
                  >
                    {{ item.name }}
                  </option>
                </SelectInput>
                <InputError :message="form.errors.inventory_item" class="mt-1.5" />
              </div>
              <div class="col-span-5">
                <InputLabel for="quantity" value="Quantity" />
                <TextInput required type="number" v-model="item.quantity" />
                <InputError :message="form.errors.quantity" class="mt-1.5" />
              </div>
              <div class="col-span-2" v-if="itemIndex !== 0">
                <PrimaryButton
                  type="button"
                  @click="removeInventoryItem(index, itemIndex)"
                  class="w-full justify-center"
                  color="red"
                  >Remove</PrimaryButton
                >
              </div>
            </div>
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
