<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

interface BreadcrumbItem {
    title: string;
    href: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Customer Service', href: '/customerservice' },
];

const form = useForm({
    judul: 'Feedback',
    message: '',
    portfolio: null as File | null,
});

const portfolioInput = ref<HTMLInputElement | null>(null);
const mails = ref<Array<any>>([]);
const isLoading = ref(true);
const error = ref<string | null>(null);
const showModal = ref(false);
const selectedMail = ref<any>(null);
let pollingInterval: number | null = null;

const submit = async () => {
    form.clearErrors();
    form.processing = true;
    try {
        const formData = new FormData();
        formData.append('judul', form.judul);
        formData.append('message', form.message);
        if (form.portfolio) {
            formData.append('portfolio', form.portfolio);
        }
        await axios.post('/api/customerservice', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });

        // Refresh data after submission
        await fetchMails();
        form.reset();
        if (portfolioInput.value) {
            portfolioInput.value.value = '';
        }
    } catch (err: any) {
        error.value = err?.response?.data?.message || 'Gagal mengirim pesan';
    } finally {
        form.processing = false;
    }
};

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.portfolio = target.files[0];
    }
};

const fetchMails = async () => {
    try {
        const response = await axios.get('/api/customerservice');
        // Only update if there are changes
        if (JSON.stringify(response.data.data) !== JSON.stringify(mails.value)) {
            mails.value = response.data.data || [];
        }
    } catch (err) {
        console.error(err);
        error.value = 'Gagal memuat riwayat pesan';
    } finally {
        isLoading.value = false;
    }
};

const openMailDetail = async (mailId: number) => {
    try {
        const response = await axios.get(`/api/customerservice/${mailId}`);
        selectedMail.value = response.data.data;
        showModal.value = true;
    } catch (err) {
        console.error(err);
        error.value = 'Gagal memuat detail pesan';
    }
};

// Start polling when component mounts
onMounted(() => {
    fetchMails();
    pollingInterval = window.setInterval(fetchMails, 2000); // Poll every 2 seconds
});

// Clean up interval when component unmounts
onUnmounted(() => {
    if (pollingInterval) {
        clearInterval(pollingInterval);
    }
});
</script>

<template>
    <Head title="Customer Service" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">
            <!-- Customer Service Card -->
            <div class="rounded-lg bg-white p-8 shadow dark:bg-gray-800 border border-black/40">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Customer Service</h2>
                <p class="mt-3 text-gray-600 dark:text-gray-400">
                    Silakan isi form berikut untuk mengirim pesan ke admin. Kami akan segera merespons pesan Anda.
                </p>

                <form @submit.prevent="submit" class="mt-8 space-y-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Pesan</label>
                        <select
                            id="judul"
                            v-model="form.judul"
                            class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#AE7A42]-700 focus:ring-[#AE7A42]-700 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 p-2.5 border border-black/40"
                        >
                            <option value="Feedback">Feedback</option>
                            <option value="Request menjadi designer">Request menjadi designer</option>
                            <option value="Request menjadi kontraktor">Request menjadi kontraktor</option>
                        </select>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pesan</label>
                        <textarea
                            id="message"
                            v-model="form.message"
                            rows="6"
                            class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-700 focus:ring-amber-700 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 p-3 border border-black/40"
                            placeholder="Tulis pesan Anda di sini..."
                            required
                        ></textarea>
                    </div>

                    <div v-if="form.judul.includes('Request')">
                        <label for="portfolio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Portfolio (PDF/DOC/Image, max 10MB)</label>
                        <input
                            ref="portfolioInput"
                            type="file"
                            id="portfolio"
                            @change="handleFileChange"
                            class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-amber-100 file:py-3 file:px-4 file:text-sm file:font-semibold file:text-amber-800 hover:file:bg-amber-200 dark:file:bg-amber-900 dark:file:text-amber-100 dark:hover:file:bg-amber-800"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                        />
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Unggah portfolio Anda jika meminta untuk menjadi designer/kontraktor (maksimal 10MB)
                        </p>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-md bg-[#AE7A42] px-6 py-3 text-sm font-medium text-white shadow-sm hover:bg-[#8f6335] focus:outline-none focus:ring-2 focus:ring-[#AE7A42] focus:ring-offset-2 disabled:opacity-50 dark:bg-[#AE7A42] dark:hover:bg-[#704824]"
                        >
                            <span v-if="form.processing">Mengirim...</span>
                            <span v-else>Kirim Pesan</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Riwayat Pesan -->
            <div class="mt-8 mb-8">
                <h3 class="text-[24px] ml-4 font-bold mb-4 text-gray-800 dark:text-gray-200">Riwayat Pesan Anda</h3>
                <div v-if="isLoading" class="flex flex-col items-center justify-center py-8 text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-[#AE7A42] mb-4"></div>
                    <p class="text-black mt-2">Loading mails...</p>
                </div>
                <div v-else-if="error" class="text-center py-12 text-red-500">
                    <p>{{ error }}</p>
                </div>
                <div v-else-if="mails.length === 0" class="text-center py-12 text-gray-500">
                    <p>Belum ada pesan yang dikirim.</p>
                </div>
                <div v-else class="space-y-4">
                    <div 
                        v-for="mail in mails" 
                        :key="mail.id"
                        @click="openMailDetail(mail.id)"
                        class="cursor-pointer flex justify-between items-center p-6 bg-white border border-black/40 rounded-lg shadow hover:bg-gray-50 transition-colors"
                    >
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ mail.judul }}</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Role: {{ mail.role }}
                                <span v-if="mail.reply"
                                    class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                    Dibalas
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
        </div>

        <!-- Mail Detail Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black/70 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
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
                                                
                                <!-- Format penanggalan yang lebih baik -->
                                <div class="text-gray-500">
                                : {{
                                    new Date(selectedMail?.created_at).toLocaleString('id-ID', {
                                    day: 'numeric',
                                    month: 'long',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false
                                    })
                                }}
                                </div>
                                
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
                                <div class="mt-4 bg-gray-100 p-4 rounded-md text-center">
                                    <img 
                                        v-if="selectedMail.portfolio.mime_type && selectedMail.portfolio.mime_type.startsWith('image/')"
                                        :src="selectedMail.portfolio.url"
                                        alt="Portfolio preview"
                                        class="max-w-full h-auto max-h-60 object-contain mx-auto rounded"
                                    />
                                    <div v-else>
                                        <p class="text-gray-600 mb-2">File portfolio ({{ selectedMail.portfolio.mime_type }})</p>
                                        <a 
                                            :href="selectedMail.portfolio.url"
                                            target="_blank"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#AE7A42] hover:bg-[#9c6d3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#AE7A42]"
                                        >
                                            View Portfolio
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Existing Reply Card (conditional) -->
                        <div v-if="selectedMail?.reply !== null" class="bg-white border border-[#AE7A42]/20 rounded-lg shadow-sm overflow-hidden">
                            <div class="px-4 py-5 sm:p-6 bg-[#AE7A42]/10">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-medium text-[#AE7A42]">Balasan Admin</h3>
                                    <span class="text-sm text-[#AE7A42]/80">
                                        {{ selectedMail.updated_at }}
                                    </span>
                                </div>
                                <div class="mt-4 bg-[#AE7A42]/5 p-4 rounded-md">
                                    <p class="whitespace-pre-line text-[#AE7A42]">{{ selectedMail.reply }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
@media (max-width: 640px) {
    .mx-auto {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }
    .rounded-lg {
        padding: 1.25rem !important;
    }
}
</style>