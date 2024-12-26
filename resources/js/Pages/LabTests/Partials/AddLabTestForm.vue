<script setup>
import { useForm } from "@inertiajs/vue3";
import { reactive } from "vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import InputError from "@/Components/Forms/InputError.vue";
import TwoColumnWrapper from "@/Components/Forms/TwoColumnWrapper.vue";
import SelectInput from "@/Components/Forms/SelectInput.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import Loader from "@/Components/Forms/Loader.vue";
import ButtonWrapper from "@/Components/Forms/ButtonWrapper.vue";
import { useToast } from "vue-toast-notification";

const $toast = useToast({ position: "top-right" });

const props = defineProps({
  categories: Array,
});

const form = useForm({
  name: "",
  category_id: "",
  price: "",
  senior_price: "",
});

const addTest = () => {
  form.post(route("lab-tests.store"), {
    errorBag: "addTest",
    preserveScroll: true,
    onSuccess: () => {
      $toast.success("Lab Test Created Successfully!");
      form.reset();
    },
  });
};
</script>
<template>
  <div>
    <h1>Add New Lab Test Form</h1>
    <form
      @submit.prevent="addTest"
      class="bg-white rounded-md mt-2.5 mb-[6rem] md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <h2 class="font-semibold md:mb-4 mb-2">Lab Test Details</h2>

      <!-- Test Name and Category -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="name" value="Test Name" />
          <TextInput autofocus v-model="form.name" placeholder="" />
          <InputError :message="form.errors.name" class="mt-1.5" />
        </template>
        <template v-slot:col2>
          <InputLabel for="category" value="Category" />
          <SelectInput v-model="form.category_id">
            <option value="">Select Category</option>
            <option
              v-for="category in props.categories"
              :key="category.slug"
              :value="category.id"
            >
              {{ category.name }}
            </option>
          </SelectInput>
          <InputError :message="form.errors.category_id" class="mt-1.5" />
        </template>
      </TwoColumnWrapper>

      <!-- Regular Price and Senior Price -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="price" value="Regular Price (PHP)" />
          <TextInput v-model="form.price" type="number" placeholder="" />
          <InputError :message="form.errors.price" class="mt-1.5" />
        </template>
        <!-- <template v-slot:col2>
          <InputLabel for="senior_price" value="Senior/PWD Price (PHP)" />
          <TextInput v-model="form.senior_price" placeholder="" type="number" />
          <InputError :message="form.errors.senior_price" class="mt-1.5" />
        </template> -->
      </TwoColumnWrapper>

      <ButtonWrapper>
        <Loader v-if="form.processing" />
        <PrimaryButton :class="{ 'opacity-25': form.processing }">
          <span v-if="!form.processing">Save</span>
          <span v-else>Saving...</span>
        </PrimaryButton>
      </ButtonWrapper>
    </form>
  </div>
</template>
