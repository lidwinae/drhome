<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import axios from 'axios';

const breadcrumbs = [
  { title: 'Email', href: '/admin/email' },
];

const form = useForm({
  recipient: '',
  subject: '',
  message: '',
});

const successMessage = ref('');
const errorMessage = ref('');
const emailHistory = ref<Array<any>>([]);
const isSending = ref(false); // New loading state

const fetchHistory = async () => {
  try {
    const res = await axios.get('/api/email/history');
    emailHistory.value = res.data.data;
  } catch (err) {
    console.error('Error fetching history:', err);
    errorMessage.value = 'Gagal memuat riwayat email';
  }
};

const submit = async () => {
  isSending.value = true; // Set loading state
  errorMessage.value = ''; // Clear previous errors
  
  try {
    const response = await axios.post('/api/email/send', form.data());

    if (response.data.success) {
      successMessage.value = 'Email berhasil dikirim!';
      form.reset();
      await fetchHistory();

      setTimeout(() => {
        successMessage.value = '';
      }, 5000);
    } else {
      errorMessage.value = response.data.message || 'Gagal mengirim email';
    }
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Terjadi kesalahan saat mengirim email';
    console.error('Error sending email:', error);
  } finally {
    isSending.value = false; // Reset loading state
  }
};

onMounted(() => {
  fetchHistory();
});
</script>

<template>
  <Head title="Send Email" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="mx-12 mt-10 space-y-10 mb-12">

      <!-- Form Section -->
      <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Send Email</h1>

        <!-- Success Message -->
        <div v-if="successMessage" class="mb-4 p-4 bg-green-100 text-green-700 rounded">
          {{ successMessage }}
        </div>

        <!-- Error Message -->
        <div v-if="errorMessage" class="mb-4 p-4 bg-red-100 text-red-700 rounded">
          {{ errorMessage }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
          <div class="mb-4">
            <label for="recipient" class="block text-sm font-medium text-gray-700">Penerima Email</label>
            <input
              v-model="form.recipient"
              type="email"
              id="recipient"
              placeholder="example@email.com"
              class="mt-1 p-4 block w-full rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              required
              :disabled="isSending"
            >
          </div>

          <div class="mb-4">
            <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
            <input
              v-model="form.subject"
              type="text"
              id="subject"
              placeholder="Enter email subject"
              class="mt-1 p-4 block w-full rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              required
              :disabled="isSending"
            >
          </div>

          <div class="mb-4">
            <label for="message" class="block text-sm font-medium text-gray-700 my-2">Message</label>
            <textarea
              v-model="form.message"
              id="message"
              rows="6"
              placeholder="Write your message here..."
              class="mt-1 p-4 block w-full rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              required
              :disabled="isSending"
            ></textarea>
          </div>

          <div class="flex justify-end">
            <button
              type="submit"
              class="px-4 py-2 text-white rounded-md hover:opacity-90 disabled:opacity-50 flex items-center justify-center min-w-32"
              :style="{ backgroundColor: '#AE7A42' }"
              :disabled="isSending"
            >
              <span v-if="!isSending">Kirim Email</span>
              <span v-else class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mengirim...
              </span>
            </button>
          </div>
        </form>
      </div>

      <!-- Email History Section -->
      <div class="space-y-6">
        <h2 class="text-xl font-semibold mb-4">Riwayat Email</h2>
        <div v-if="emailHistory.length === 0" class="text-center py-4 text-gray-500">
          Belum ada riwayat email
        </div>

        <div v-for="item in emailHistory" :key="item.id" class="bg-white p-6 shadow-md rounded-lg border border-gray-300 space-y-4">
          <div class="flex space-x-4">
            <strong class="text-sm font-semibold text-gray-700 w-32">Email Penerima</strong>
            <p class="text-gray-600 flex-1">: {{ item.recipient }}</p>
          </div>
          <div class="flex space-x-4">
            <strong class="text-sm font-semibold text-gray-700 w-32">Subject</strong>
            <p class="text-gray-600 flex-1">: {{ item.subject }}</p>
          </div>
          <div class="flex space-x-4">
            <strong class="text-sm font-semibold text-gray-700 w-32">Message</strong>
            <p class="text-gray-600 flex-1">: {{ item.message }}</p>
          </div>
          <div class="flex space-x-4">
            <strong class="text-sm font-semibold text-gray-700 w-32">Waktu</strong>
            <p class="text-gray-600 flex-1">: {{ new Date(item.created_at).toLocaleString() }}</p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
