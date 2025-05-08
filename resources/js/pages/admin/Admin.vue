<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Admin',
    href: '/admin',
  },
];

const mails = ref<Array<any>>([]);
const isLoading = ref(true);
const error = ref<string | null>(null);
const showModal = ref(false);
const selectedMail = ref<any>(null);
const submitError = ref<string | null>(null);

// Gunakan Inertia Form khusus untuk reply
const replyForm = useForm({
  reply: '',
});

// Fetch mails list dengan axios (agar tetap bekerja)
const fetchMails = async () => {
  try {
    const response = await axios.get('/api/admins');
    mails.value = response.data.data || [];
  } catch (err) {
    console.error('Error:', err);
    error.value = 'Gagal memuat mails';
  } finally {
    isLoading.value = false;
  }
};

// Fetch single mail detail dengan axios
const openMailDetail = async (mailId: number) => {
  try {
    const response = await axios.get(`/api/admins/${mailId}`);
    selectedMail.value = response.data.data;
    replyForm.reply = selectedMail.value.reply || '';
    showModal.value = true;
  } catch (err) {
    console.error('Error loading mail detail:', err);
    error.value = 'Gagal memuat detail mail';
  }
};

const submitReply = () => {
  if (!replyForm.reply.trim()) {
    submitError.value = 'Isi balasan tidak boleh kosong';
    return;
  }

  replyForm.patch(`/admin/mails/${selectedMail.value.id}/reply`, {
    preserveScroll: true,
    onSuccess: () => {
      // Update data lokal
      selectedMail.value.reply = replyForm.reply;
      selectedMail.value.updated_at = new Date().toISOString();
      
      // Update status di list mails
      const updatedMail = mails.value.find(m => m.id === selectedMail.value.id);
      if (updatedMail) {
        updatedMail.reply = replyForm.reply;
        updatedMail.updated_at = new Date().toISOString();
      }
    },
    onError: (errors) => {
      submitError.value = errors.reply || 'Gagal menyimpan balasan';
    }
  });
};

onMounted(fetchMails);
</script>

<template>
  <Head title="Admin" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="mx-12 mt-10 space-y-6">
      <h4>Daftar Mails to Admin:</h4>
      <!-- Mail List -->
      <div v-if="isLoading" class="text-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
        <p class="mt-4 text-gray-600">Loading mails...</p>
      </div>
      
      <div v-else-if="error" class="text-center py-12 text-red-500">
        <p>{{ error }}</p>
      </div>
      
      <div v-else-if="mails.length === 0" class="text-center py-12 text-gray-500">
        <p>Tidak ada mail yang tersedia</p>
      </div>

      <div v-else class="space-y-4">
        <div 
          v-for="mail in mails" 
          :key="mail.id"
          @click="openMailDetail(mail.id)"
          class="cursor-pointer flex justify-between items-center p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition-colors"
        >
          <div>
            <h3 class="text-lg font-medium text-gray-900">{{ mail.judul }}</h3>
            <p class="text-sm text-gray-500 mt-1">
              Role: {{ mail.role }}
              <span v-if="mail.reply"
                    class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                Dibalas pada {{ new Date(mail.updated_at).toLocaleDateString() }}
              </span>
              <span v-else
                    class="ml-2 px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                Belum dibalas
              </span>
            </p>
          </div>
          <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">{{ new Date(mail.created_at).toLocaleDateString() }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Mail Detail Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black/70 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 space-y-6">
          <!-- Modal Header -->
          <div class="flex justify-between items-start">
            <div>
              <h2 class="mt-4 mb-2 text-2xl font-bold">{{ selectedMail?.judul }}</h2>
              <div class="mt-4 grid grid-cols-[auto_1fr] gap-x-2 gap-y-1 text-sm">
                <div class="text-gray-600">Name</div>
                <div class="text-gray-600">: {{ selectedMail?.user?.name }}</div>

                <div class="text-gray-600">Email</div>
                <div class="text-gray-600">: {{ selectedMail?.user?.email }}</div>

                <div class="text-gray-500">Role</div>
                <div class="text-gray-500">: {{ selectedMail?.role }}</div>

                <div class="text-gray-500">Dikirim</div>
                <div class="text-gray-500">: {{ new Date(selectedMail?.created_at).toLocaleString() }}</div>
              </div>
            </div>
            <button 
              @click="showModal = false"
              class="text-gray-500 hover:text-gray-700 transition-colors"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Main Content Container -->
          <div class="space-y-6">
            <!-- Message Content Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
              <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900">Isi Pesan</h3>
                <div class="mt-4 bg-gray-50 p-4 rounded-md">
                  <p class="whitespace-pre-line text-gray-700">{{ selectedMail?.message }}</p>
                </div>
              </div>
            </div>

            <!-- Portfolio Card (conditional) -->
            <div v-if="selectedMail?.portfolio" class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
              <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900">Portfolio</h3>
                <div class="mt-4 bg-gray-100 p-4 rounded-md">
                  <img 
                    v-if="selectedMail.portfolio.mime_type.startsWith('image/')"
                    :src="`data:${selectedMail.portfolio.mime_type};base64,${selectedMail.portfolio.data}`"
                    alt="Portfolio preview"
                    class="max-w-full h-auto max-h-60 object-contain mx-auto rounded"
                  />
                  <div v-else class="text-center">
                    <p class="text-gray-600 mb-2">File portfolio ({{ selectedMail.portfolio.mime_type }})</p>
                    <a 
                      :href="`data:${selectedMail.portfolio.mime_type};base64,${selectedMail.portfolio.data}`"
                      download="portfolio"
                      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#AE7A42] hover:bg-[#9c6d3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#AE7A42]"
                    >
                      Download Portfolio
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <!-- Existing Reply Card (conditional) -->
            <div v-if="selectedMail?.reply !== null" class="bg-white border border-[#AE7A42]/20 rounded-lg shadow-sm overflow-hidden">
              <div class="px-4 py-5 sm:p-6 bg-[#AE7A42]/10">
                <div class="flex justify-between items-center">
                  <h3 class="text-lg font-medium text-[#AE7A42]">Balasan Anda</h3>
                  <span class="text-sm text-[#AE7A42]/80">
                    {{ new Date(selectedMail.updated_at).toLocaleString() }}
                  </span>
                </div>
                <div class="mt-4 bg-[#AE7A42]/5 p-4 rounded-md">
                  <p class="whitespace-pre-line text-[#AE7A42]">{{ selectedMail.reply }}</p>
                </div>
              </div>
            </div>

            <!-- Reply Form Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-4">
              <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900">Balas Pesan</h3>
                <form @submit.prevent="submitReply" class="mt-4 space-y-4">
                  <div>
                    <textarea
                      v-model="replyForm.reply"
                      class="w-full border border-gray-300 rounded-md shadow-sm focus:border-[#AE7A42] focus:ring-[#AE7A42] min-h-[150px] p-3"
                      placeholder="Tulis balasan Anda..."
                      :disabled="replyForm.processing"
                    ></textarea>
                    <p v-if="submitError" class="mt-2 text-sm text-red-600">{{ submitError }}</p>
                  </div>
                  <div class="flex justify-end space-x-3">
                    <button
                      type="button"
                      @click="showModal = false"
                      class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#AE7A42]"
                      :disabled="replyForm.processing"
                    >
                      Tutup
                    </button>
                    <button
                      type="submit"
                      class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#AE7A42] hover:bg-[#9c6d3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#AE7A42] disabled:opacity-50"
                      :disabled="replyForm.processing"
                    >
                      <span v-if="!replyForm.processing">
                        {{ selectedMail?.reply !== null ? 'Perbarui' : 'Kirim' }} Balasan
                      </span>
                      <span v-else>Menyimpan...</span>
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>