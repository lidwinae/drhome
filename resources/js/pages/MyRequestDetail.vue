<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { MessageSquareText } from 'lucide-vue-next';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    request: {
        type: Object,
        required: true
    },
    type: {
        type: String,
        required: true // 'contractor' atau 'designer'
    }
});

const requestData = ref(props.request); // Use ref for request data that can be updated
const openAccStatus = ref(props.request.open_acc);
const amount = ref(props.request.budget || '');
const paymentLoading = ref(false);
const paymentError = ref<string|null>(null);
const paymentSuccess = ref<string|null>(null);

const openAccLoading = ref(false);
const openAccError = ref<string|null>(null);
const openAccSuccess = ref<string|null>(null);

// Polling interval
let pollingInterval: number | null = null;

const statuses = computed(() => {
    if (props.type === 'contractor') {
        return [
            { id: 'design_submitted', label: 'Design Submitted' },
            { id: 'payment', label: 'Payment' },
            { id: 'construction_start', label: 'Construction Start' },
            { id: 'construction_end', label: 'Construction End' }
        ];
    } else {
        return [
            { id: 'form_submitted', label: 'Form Submitted' },
            { id: 'payment', label: 'Payment' },
            { id: 'design_start', label: 'Design Start' },
            { id: 'design_end', label: 'Design End' }
        ];
    }
});

const currentStatus = computed(() => requestData.value.progress);

function getStatusLabel(request: any, type: string) {
    if (type === 'contractor') {
        if (request.status === 'rejected') return 'Rejected';
        if (request.status === 'accepted' && request.progress === 'construction_end') return 'Finished';
        if (request.status === 'accepted' && request.progress === 'construction_start') return 'Construction Started';
        if (request.status === 'accepted' && request.progress === 'payment') return 'Waiting Payment';
        if (request.status === 'accepted' && request.progress === 'design_submitted') return 'Design Submitted';
        if (request.status === 'waiting') return 'Waiting';
        if (request.status === 'finished') return 'Finished';
    } else if (type === 'designer') {
        if (request.status === 'rejected') return 'Rejected';
        if (request.status === 'accepted' && request.progress === 'design_end') return 'Finished';
        if (request.status === 'accepted' && request.progress === 'design_start') return 'Design Started';
        if (request.status === 'accepted' && request.progress === 'payment') return 'Waiting Payment';
        if (request.status === 'accepted' && request.progress === 'form_submitted') return 'Form Submitted';
        if (request.status === 'waiting') return 'Waiting';
        if (request.status === 'finished') return 'Finished';
    }
    return request.status;
}

function getStatusClass(request: any, type: string) {
    if (request.status === 'rejected') {
        return 'bg-red-100 text-red-800';
    }
    if (
        request.status === 'finished' ||
        (type === 'contractor' && request.progress === 'construction_end') ||
        (type === 'designer' && request.progress === 'design_end')
    ) {
        return 'bg-green-100 text-green-800';
    }
    if (
        (type === 'contractor' && ['design_submitted', 'payment', 'construction_start'].includes(request.progress)) ||
        (type === 'designer' && ['form_submitted', 'payment', 'design_start'].includes(request.progress))
    ) {
        return 'bg-yellow-100 text-yellow-800';
    }
    return 'bg-gray-100 text-gray-800';
}

function getTargetUser() {
    if (props.type === 'contractor') {
        return requestData.value.contractor;
    } else if (props.type === 'designer') {
        return requestData.value.designer;
    }
    return null;
}

function formatCurrency() {
    if (typeof amount.value === 'string') {
        amount.value = amount.value.replace(/[^\d]/g, '');
    }
}

const isFormValid = computed(() => {
    return !!amount.value && parseFloat(amount.value) > 0;
});

// Fungsi untuk update open_acc
async function openAcc() {
    openAccLoading.value = true;
    openAccError.value = null;
    openAccSuccess.value = null;
    try {
        await axios.post(`/my-request/${props.type}/${requestData.value.id}/open-acc`);
        openAccSuccess.value = 'Open ACC berhasil diaktifkan!';
        openAccStatus.value = true;
        // Fetch updated data after successful open acc
        await fetchData();
    } catch (e: any) {
        openAccError.value = e.response?.data?.message || 'Gagal mengaktifkan Open ACC';
    } finally {
        openAccLoading.value = false;
    }
}

async function submitPayment() {
    paymentLoading.value = true;
    paymentError.value = null;
    try {
        await axios.post(`/my-request/${props.type}/${requestData.value.id}/pay`, { amount: amount.value });
        // Fetch updated data after successful payment
        await fetchData();
    } catch (e: any) {
        paymentError.value = e.response?.data?.message || 'Failed to process payment';
    } finally {
        paymentLoading.value = false;
    }
}

function getCurrentUserId() {
    return requestData.value.client_id;
}

// Fungsi untuk mengambil data terbaru
async function fetchData() {
    try {
        const response = await axios.get(`/api/myrequest/${requestData.value.id}`);
        requestData.value = response.data.request;
        // Update local refs with new data
        openAccStatus.value = response.data.request.open_acc;
        amount.value = response.data.request.budget || '';
    } catch (error) {
        console.error('Error fetching updated data:', error);
    }
}

// Setup polling ketika komponen dimount
onMounted(() => {
    // Mulai polling setiap 2 detik
    pollingInterval = window.setInterval(fetchData, 2000);
});

// Bersihkan interval ketika komponen di-unmount
onUnmounted(() => {
    if (pollingInterval) {
        clearInterval(pollingInterval);
    }
});
</script>

<template>
    <Head title="My Request Detail" />
    <AppLayout>
        <main class="bg-gray-50 w-full min-h-screen p-4 md:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                <section class="flex flex-col lg:flex-row gap-6 lg:items-start">
                    <!-- Card Detail -->
                    <div class="bg-white rounded-2xl shadow-md p-6 lg:w-2/3 flex flex-col items-center">
                        <img
                            class="h-24 w-24 rounded-full object-cover border mb-4"
                            :src="getTargetUser()?.avatar ? '/storage/' + getTargetUser().avatar : 'https://ui-avatars.com/api/?name=' + (getTargetUser()?.name || 'User')"
                            :alt="getTargetUser()?.name"
                        />
                        <div class="font-semibold text-[28px] text-gray-900 mb-2 flex items-center gap-2">
                            <span
                                :class="type === 'designer' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'"
                                class="mr-1 px-2 py-0.5 rounded text-xs font-semibold"
                            >
                                My Request to {{ type === 'designer' ? 'Designer' : 'Contractor' }}
                            </span>
                            {{ getTargetUser()?.name }}
                        </div>
                        <div class="mb-4">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                :class="getStatusClass(requestData, type)"
                            >
                                {{ getStatusLabel(requestData, type) }}
                            </span>
                        </div>
                        <!-- Pesan progress -->
                        <div
                            v-if="requestData.progress === 'design_start' || requestData.progress === 'construction_start'"
                            class="mb-4 mt-2 text-[17px] text-[#AE7A42] bg-[#FFF7ED] border border-[#AE7A42] rounded-lg px-4 py-3 w-full"
                        >
                            <span class="font-semibold">*</span>
                            Silahkan chat dengan {{ type === 'designer' ? 'designer' : 'contractor' }} Anda untuk melanjutkan progress
                        </div>
                        <!-- Detail Table -->
                        <div class="w-full mb-4 mt-8">
                            <div class="flex flex-col gap-6 text-xl">
                                <div v-if="requestData.purchased_design && requestData.purchased_design.design_path" class="flex items-center gap-4">
                                    <span class="font-semibold w-48">Design File</span>
                                    <span class="flex-1">
                                        <a
                                            :href="'/storage/' + requestData.purchased_design.design_path"
                                            target="_blank"
                                            class="bg-gray-100 rounded-lg px-4 py-2 text-blue-600 underline block text-left"
                                        >
                                            View File
                                        </a>
                                    </span>
                                </div>

                                <!-- Payment Row -->
                                <div class="flex items-center gap-4" v-if="requestData.payment">
                                    <span class="font-semibold w-48">Payment</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 font-bold text-left">
                                        {{ 'IDR ' + new Intl.NumberFormat('id-ID').format(requestData.payment) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="font-semibold w-48">Budget</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 font-bold text-gray-800 text-2xl text-left">
                                        {{ requestData.budget ? 'IDR ' + new Intl.NumberFormat('id-ID').format(requestData.budget) : '-' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="font-semibold w-48">Province</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 text-left">{{ requestData.province }}</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="font-semibold w-48">City</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 text-left">{{ requestData.city }}</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="font-semibold w-48">Land Size</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 text-left">{{ requestData.land_size }} m²</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="font-semibold w-48">Land Shape</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 text-left">{{ requestData.land_shape }}</span>
                                </div>
                                <div class="flex items-center gap-4" v-if="type === 'designer'">
                                    <span class="font-semibold w-48">Sun Orientation</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 text-left">{{ requestData.sun_orientation }}</span>
                                </div>
                                <div class="flex items-center gap-4" v-if="type === 'designer'">
                                    <span class="font-semibold w-48">Wind Orientation</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 text-left">{{ requestData.wind_orientation }}</span>
                                </div>
                                <div class="flex items-center gap-4" v-if="type === 'designer' && requestData.design_reference_path">
                                    <span class="font-semibold w-48">Design Reference</span>
                                    <span class="flex-1">
                                        <a
                                            :href="'/storage/' + requestData.design_reference_path"
                                            target="_blank"
                                            class="bg-gray-100 rounded-lg px-4 py-2 text-blue-600 underline block text-left"
                                        >
                                            View File
                                        </a>
                                    </span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="font-semibold w-48">Deadline</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 text-left">
                                    {{ requestData.deadline ? new Date(requestData.deadline).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="font-semibold w-48">Notes</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 text-left">{{ requestData.notes || '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- Payment Section di dalam card detail -->
                        <div v-if="requestData.progress === 'payment'" class="w-full mt-6">
                            <div class="border-t pt-6">
                                <h3 class="text-2xl font-bold mb-4 text-[#AE7A42]">Enter Payment</h3>
                                <p class="mb-4 text-black">Silakan lakukan pembayaran sesuai nominal berikut.</p>
                                <div class="mb-4">
                                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (IDR)</label>
                                    <input
                                        type="number"
                                        id="amount"
                                        v-model="amount"
                                        step="0.01"
                                        min="0"
                                        placeholder="0.00"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#AE7A42] focus:border-[#AE7A42] required"
                                        @input="formatCurrency"
                                        :readonly="false"
                                    >
                                </div>
                                <div v-if="paymentError" class="text-red-600 mb-2">{{ paymentError }}</div>
                                <div v-if="paymentSuccess" class="text-green-600 mb-2">{{ paymentSuccess }}</div>
                                <div class="sm:self-end">
                                    <button
                                        @click="submitPayment"
                                        :disabled="!isFormValid || paymentLoading"
                                        :class="{
                                            'bg-[#AE7A42] hover:bg-[#8e6536]': isFormValid && !paymentLoading,
                                            'bg-gray-400 cursor-not-allowed': !isFormValid || paymentLoading
                                        }"
                                        class="w-20 sm:w-auto inline-flex justify-center text-white font-bold py-3 px-6 rounded-full text-[16px] transition-colors duration-200"
                                    >
                                        <span v-if="paymentLoading">Processing...</span>
                                        <span v-else>Pay</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- End Payment Section -->
                    </div>
                    <!-- Status Section (Desktop) -->
                    <div class="lg:w-1/3 flex flex-col gap-6 hidden lg:flex">
                        <!-- Status Section -->
                        <div class="status-section bg-[#AE7A42] rounded-2xl shadow-md p-6">
                            <h2 class="text-2xl font-bold text-white border-b border-[#fff7ed]/30 pb-3 mb-6">Project Progress</h2>
                            <div class="space-y-4">
                                <div v-for="(status, index) in statuses" :key="status.id" class="flex items-start">
                                    <div class="flex-shrink-0 relative">
                                        <div :class="[
                                            'h-6 w-6 rounded-full flex items-center justify-center border-2',
                                            currentStatus === status.id
                                                ? 'bg-white text-[#AE7A42] border-white'
                                                : 'bg-[#AE7A42]/30 text-white border-white/30'
                                        ]">
                                            <span v-if="currentStatus === status.id">✓</span>
                                        </div>
                                        <div v-if="index < statuses.length - 1"
                                            class="absolute left-1/2 top-6 w-0.5 h-10 -ml-px"
                                            :class="currentStatus === status.id ? 'bg-white' : 'bg-white/30'"></div>
                                    </div>
                                    <div class="ml-4">
                                        <p :class="[
                                            'text-sm font-medium',
                                            'text-white',
                                            currentStatus === status.id ? 'font-bold' : ''
                                        ]">
                                            {{ status.label }}
                                        </p>
                                        <p v-if="currentStatus === status.id" class="text-xs text-white mt-1">
                                            Current status
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Chat & Open ACC Card -->
                        <div class="flex justify-end mb-6">
                            <template v-if="(requestData.progress === 'construction_start' || requestData.progress === 'design_start')">
                                <button
                                    v-if="!openAccStatus"
                                    @click="openAcc"
                                    :disabled="openAccLoading"
                                    class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-[20px] font-medium rounded-lg shadow transition flex items-center mr-4"
                                >
                                    <span v-if="openAccLoading">Processing...</span>
                                    <span v-else>Buka ACC</span>
                                </button>
                                <span
                                    v-else
                                    class="px-6 py-3 bg-green-100 text-green-700 text-[20px] font-medium rounded-lg shadow flex items-center mr-4"
                                >
                                    ACC dibuka <span class="ml-2 text-green-600 text-2xl font-bold">✓</span>
                                </span>
                            </template>
                            <Link
                                :href="`/chat/${getCurrentUserId()}/${getTargetUser()?.id}`"
                                class="px-6 py-3 bg-[#AE7A42] hover:bg-[#8c5e30] text-white text-[20px] font-medium rounded-lg shadow transition flex items-center"
                            >
                                <MessageSquareText class="mr-4"/>Chat
                            </Link>
                        </div>
                    </div>
                </section>
                <!-- Status & Chat Section (Mobile) -->
                <div class="flex flex-col gap-6 lg:hidden">
                    <!-- Status Section -->
                    <section class="status-section bg-[#AE7A42] rounded-2xl shadow-md p-6">
                        <h2 class="text-2xl font-bold text-white border-b border-[#fff7ed]/30 pb-3 mb-6">Project Progress</h2>
                        <div class="space-y-4">
                            <div v-for="(status, index) in statuses" :key="status.id" class="flex items-start">
                                <div class="flex-shrink-0 relative">
                                    <div :class="[
                                        'h-6 w-6 rounded-full flex items-center justify-center border-2',
                                        currentStatus === status.id
                                            ? 'bg-white text-[#AE7A42] border-white'
                                            : 'bg-[#AE7A42]/30 text-white border-white/30'
                                    ]">
                                        <span v-if="currentStatus === status.id">✓</span>
                                    </div>
                                    <div v-if="index < statuses.length - 1"
                                        class="absolute left-1/2 top-6 w-0.5 h-10 -ml-px"
                                        :class="currentStatus === status.id ? 'bg-white' : 'bg-white/30'"></div>
                                </div>
                                <div class="ml-4">
                                    <p :class="[
                                        'text-sm font-medium',
                                        'text-white',
                                        currentStatus === status.id ? 'font-bold' : ''
                                    ]">
                                        {{ status.label }}
                                    </p>
                                    <p v-if="currentStatus === status.id" class="text-xs text-white mt-1">
                                        Current status
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Chat & Open ACC Card -->
                    <div class="flex justify-end mb-6">
                        <template v-if="(requestData.progress === 'construction_start' || requestData.progress === 'design_start')">
                            <button
                                v-if="!openAccStatus"
                                @click="openAcc"
                                :disabled="openAccLoading"
                                class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-[20px] font-medium rounded-lg shadow transition flex items-center mr-2"
                            >
                                <span v-if="openAccLoading">Processing...</span>
                                <span v-else>Buka ACC</span>
                            </button>
                            <span
                                v-else
                                class="px-6 py-3 bg-green-100 text-green-700 text-[20px] font-medium rounded-lg shadow flex items-center mr-2"
                            >
                                ACC dibuka <span class="ml-2 text-green-600 text-2xl font-bold">✓</span>
                            </span>
                        </template>
                        <Link
                            :href="`/chat/${getCurrentUserId()}/${getTargetUser()?.id}`"
                            class="px-6 py-3 bg-[#AE7A42] hover:bg-[#8c5e30] text-white text-[20px] font-medium rounded-lg shadow transition flex items-center"
                        >
                            <MessageSquareText class="mr-4"/>Chat
                        </Link>
                    </div>
                </div>
            </div>
        </main>
    </AppLayout>
</template>