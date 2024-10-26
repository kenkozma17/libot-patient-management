<script setup>
import { useForm, Link } from "@inertiajs/vue3";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import InputError from "@/Components/Forms/InputError.vue";
import TwoColumnWrapper from "@/Components/Forms/TwoColumnWrapper.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import Loader from "@/Components/Forms/Loader.vue";
import ButtonWrapper from "@/Components/Forms/ButtonWrapper.vue";
import { useToast } from "vue-toast-notification";
const $toast = useToast({ position: "top-right" });

const props = defineProps({
  item: Object,
});

const form = useForm({
  name: props.item.name,
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

      <!-- Name -->
      <TwoColumnWrapper>
        <template v-slot:col1>
          <InputLabel for="name" value="Name" />
          <TextInput autofocus v-model="form.name" placeholder="Glucose" />
          <InputError :message="form.errors.name" class="mt-1.5" />
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
