<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import dayjs from "dayjs";
import Pagination from "@/Components/Pagination.vue";
import { ref, computed } from "vue";
import Trash from "@/Components/Icons/Trash.vue";
import EyeBall from "@/Components/Icons/EyeBall.vue";
import { useForm, Link } from "@inertiajs/vue3";

const props = defineProps({
  notifications: Object,
});

// Pagination
const currentPage = ref(Number(props.notifications.current_page || 1));
const totalPages = props.notifications.last_page;
const isLoading = ref(false);

const markAsRead = (notificationId) => {
  const readForm = useForm({});
  readForm.post(route("notifications.mark-as-read", notificationId), {
    errorBag: "markAsRead",
    preserveScroll: true,
  });
};

const deleteNotification = (notificationId) => {
  if (confirm("Are you sure you want to delete this notification?")) {
    const deleteForm = useForm({});
    deleteForm.delete(route("notifications.destroy", notificationId), {
      errorBag: "deleteNotification",
      preserveScroll: true,
    });
  }
};

defineOptions({ layout: AdminLayout });
</script>
<template>
  <AdminContentWrapper>
    <h1>Notifications ({{ notifications.total }})</h1>
    <div class="mt-2.5 p-[1rem] bg-white rounded-md flex flex-col gap-[1rem]">
      <div
        class="grid grid-cols-12 rounded-md shadow-lg p-[1rem] border-2 border-blue-100 flex justify-between cursor-pointer hover:border-blue-400"
        v-for="notification in notifications.data"
      >
        <div class="lg:col-span-8 col-span-12">
          <p class="text-red-600" :class="{ 'font-bold': notification.read_at === null }">
            {{ notification.data.type }}
          </p>
          <p class="text-sm" :class="{ 'font-bold': notification.read_at === null }">
            {{ notification.data.message }}
          </p>
          <Link v-if="notification.data.notification == 'inventory'"
            class="mt-[1rem] text-xs text-blue-600 font-bold hover:underline"
            :href="route('inventory.show', notification.notifiable_id)"
            >View Item</Link
          >
          <Link v-if="notification.data.notification == 'patient-loans'"
            class="mt-[1rem] text-xs text-blue-600 font-bold hover:underline"
            :href="route('patient-loans.show', notification.notifiable_id)"
            >View Loan</Link
          >
        </div>
        <div class="lg:col-span-4 col-span-12 flex flex-col gap-[.5rem] items-end">
          <p class="text-xs" :class="{ 'font-bold': notification.read_at === null }">
            {{ dayjs(notification.created_at).format("MMM D, YYYY h:mm A") }}
          </p>
          <div class="flex gap-[1rem] justify-center">
            <div class="w-6 h-6 hover:bg-gray-200 rounded-full p-[.25rem]">
              <EyeBall @click="markAsRead(notification.id)" class="w-full h-full" />
            </div>
            <div class="w-6 h-6 hover:bg-gray-200 rounded-full p-[.25rem]">
              <Trash @click="deleteNotification(notification.id)" class="w-full h-full" />
            </div>
          </div>
        </div>
      </div>

      <Pagination
        class="md:mt-[1.5rem] mt-[2rem]"
        v-if="notifications.data.length"
        :current-page="currentPage"
        :is-loading="isLoading"
        :total-pages="totalPages"
        :next-page="notifications.next_page_url"
        :prev-page="notifications.prev_page_url"
      />
    </div>
  </AdminContentWrapper>
</template>
