<script setup>
import { useForm, Link } from "@inertiajs/vue3";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import InputError from "@/Components/Forms/InputError.vue";
import TwoColumnWrapper from "@/Components/Forms/TwoColumnWrapper.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import Loader from "@/Components/Forms/Loader.vue";
import ButtonWrapper from "@/Components/Forms/ButtonWrapper.vue";
import SelectInput from "@/Components/Forms/SelectInput.vue";
import { useToast } from "vue-toast-notification";
const $toast = useToast({ position: "top-right" });
import { ref } from "vue";

const props = defineProps({
  item: Object,
  categories: Array,
});

const units = ref(["pcs", "boxes", "liters"]);
const classifications = ref(["reagent", "supplies"]);

const form = useForm({
  name: props.item.name,
  category_id: props.item.category_id,
  unit: props.item.unit,
  low_stock_limit: props.item.low_stock_limit,
  days_before_expiration_limit: props.item.days_before_expiration_limit,
  classification: props.item.classification,
});
const updateItem = () => {
  form.put(route("inventory.update", props.item.id), {
    errorBag: "updateItem",
    preserveScroll: true,
    onSuccess: () => {
      $toast.success("Item Updated Successfully!");
    },
  });
};
</script>
<template>
  <div>
    <h1>Edit Inventory Item Form</h1>
    <form
      @submit.prevent="updateItem"
      class="bg-white rounded-md mt-2.5 mb-[6rem] md:px-[1.9rem] px-[1.25rem] md:py-[1.4rem] py-[1.125rem]"
    >
      <h2 class="font-semibold md:mb-4 mb-2">Item Details</h2>

      <!-- Name and Category -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="name" value="Name" />
          <TextInput autofocus v-model="form.name" placeholder="Glucose" />
          <InputError :message="form.errors.name" class="mt-1.5" />
        </template>

        <template v-slot:col2>
          <InputLabel for="category" value="Category" />
          <SelectInput v-model="form.category_id">
            <option value="">Select Category</option>
            <option
              :selected="category.id == form.category_id"
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

      <!-- Notice Limits -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="low_stock_limit" value="Low Stock Notice (Quantity)" />
          <TextInput
            autofocus
            type="number"
            v-model="form.low_stock_limit"
            placeholder="0"
          />
          <InputError :message="form.errors.low_stock_limit" class="mt-1.5" />
        </template>

        <template v-slot:col2>
          <InputLabel
            for="days_before_expiration_limit"
            value="Expiration Notice (Days)"
          />
          <TextInput
            autofocus
            type="number"
            v-model="form.days_before_expiration_limit"
            placeholder="0"
          />
          <InputError
            :message="form.errors.days_before_expiration_limit"
            class="mt-1.5"
          />
        </template>
      </TwoColumnWrapper>

      <!-- Unit and Classification -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="unit" value="Unit" />
          <SelectInput v-model="form.unit">
            <option value="">Select Unit</option>
            <option v-for="(unit, key) in units" :key="unit" :value="unit">
              {{ unit }}
            </option>
          </SelectInput>
          <InputError :message="form.errors.unit" class="mt-1.5" />
        </template>

        <template v-slot:col2>
            <InputLabel for="classifications" value="Classification" />
            <SelectInput v-model="form.classification">
              <option value="">Select Classification</option>
              <option
                v-for="(classification, key) in classifications"
                :key="classification"
                :value="classification"
              >
                {{ classification }}
              </option>
            </SelectInput>
            <InputError :message="form.errors.classification" class="mt-1.5" />
          </template>
      </TwoColumnWrapper>

      <ButtonWrapper>
        <Loader v-if="form.processing" />
        <Link
          v-if="!form.processing"
          :href="route('inventory.show', props.item.id)"
          class="mr-4"
        >
          Cancel
        </Link>
        <PrimaryButton :class="{ 'opacity-25': form.processing }">
          <span v-if="!form.processing">Update</span>
          <span v-else>Updating...</span>
        </PrimaryButton>
      </ButtonWrapper>
    </form>
  </div>
</template>
