<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import axios from 'axios';
import { MessageSquareText } from 'lucide-vue-next';

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

const loading = ref(false);
const error = ref<string|null>(null);

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

const currentStatus = computed(() => props.request.progress);

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

function getClient() {
    return props.request.client;
}

function getTargetUser() {
    if (props.type === 'contractor') {
        return props.request.contractor;
    } else if (props.type === 'designer') {
        return props.request.designer;
    }
    return null;
}

async function updateStatus(status: string) {
    loading.value = true;
    error.value = null;
    try {
        await axios.post(`/request/${props.request.id}/status`, { status, type: props.type });
        window.location.reload();
    } catch (e: any) {
        error.value = e.response?.data?.message || 'Failed to update status';
    } finally {
        loading.value = false;
    }
}

const purchasedForm = ref({
    design_name: '',
    design_country: '',
    design_specialty: '',
    design_path: null, // now a File object
    price: ''
});

function handleFileChange(e: Event) {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        purchasedForm.value.design_path = target.files[0];
    } else {
        purchasedForm.value.design_path = null;
    }
}
const purchasedLoading = ref(false);
const purchasedError = ref<string|null>(null);
const purchasedSuccess = ref<string|null>(null);

async function submitPurchasedDesign() {
    purchasedLoading.value = true;
    purchasedError.value = null;
    purchasedSuccess.value = null;
    try {
        const formData = new FormData();
        formData.append('request_designer_id', props.request.id);
        formData.append('design_name', purchasedForm.value.design_name);
        formData.append('design_country', purchasedForm.value.design_country);
        formData.append('design_specialty', purchasedForm.value.design_specialty);
        formData.append('design_path', purchasedForm.value.design_path); // file
        formData.append('price', purchasedForm.value.price);

        await axios.post('/purchased-designs/from-request-designer', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        window.location.reload();
        purchasedSuccess.value = 'Purchased design berhasil disimpan!';
        purchasedForm.value = {
            design_name: '',
            design_country: '',
            design_specialty: '',
            design_path: null,
            price: ''
        };
    } catch (e: any) {
        purchasedError.value = e.response?.data?.message || 'Gagal menyimpan purchased design';
    } finally {
        purchasedLoading.value = false;
    }
}

async function finishConstruction() {
    loading.value = true;
    error.value = null;
    try {
        await axios.post(`/request/${props.request.id}/finish-construction`, {
            type: props.type
        });
        window.location.reload();
    } catch (e: any) {
        error.value = e.response?.data?.message || 'Failed to finish construction';
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <Head title="Request Detail" />
    <AppLayout>
        <main class="bg-gray-50 w-full min-h-screen p-4 md:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                <section class="flex flex-col lg:flex-row gap-6 lg:items-start">
                    <!-- Card Detail -->
                    <div class="bg-white rounded-2xl shadow-md p-6 lg:w-2/3 flex flex-col items-center">
                        <img
                            class="h-24 w-24 rounded-full object-cover border mb-4"
                            :src="getClient()?.avatar ? '/storage/' + getClient().avatar : 'https://ui-avatars.com/api/?name=' + (getClient()?.name || 'User')"
                            :alt="getClient()?.name"
                        />
                        <div class="font-semibold text-[28px] text-gray-900 mb-2">{{ getClient()?.name }}</div>
                        <div class="mb-4">
                            <span
                                class="bg-gray-300 px-2 inline-flex text-xs leading-5 font-semibold rounded-full mr-2"
                            >
                                Client
                            </span>
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                :class="getStatusClass(request, type)"
                            >
                                {{ getStatusLabel(request, type) }}
                            </span>
                        </div>
                        <!-- Pesan progress -->
                        <div
                            v-if="request.progress === 'design_start' || request.progress === 'construction_start'"
                            class="mb-4 mt-2 text-[17px] text-[#AE7A42] bg-[#FFF7ED] border border-[#AE7A42] rounded-lg px-4 py-3 w-full"
                        >
                            <span class="font-semibold">*</span>
                            Silahkan chat dengan client anda untuk bisa menyelesaikan progress
                        </div>
                        <!-- Detail Table -->
                        <div class="w-full mb-4 mt-8">
                            <div class="flex flex-col gap-6 text-xl">
                                <div v-if="request.purchased_design && request.purchased_design.design_path" class="flex items-center gap-4">
                                    <span class="font-semibold w-48">Design File</span>
                                    <span class="flex-1">
                                        <a
                                            :href="'/storage/' + request.purchased_design.design_path"
                                            target="_blank"
                                            class="bg-gray-100 rounded-lg px-4 py-2 text-blue-600 underline block text-left"
                                        >
                                            View File
                                        </a>
                                    </span>
                                </div>
                                
                                <!-- Payment Row -->
                                <div class="flex items-center gap-4" v-if="request.payment">
                                    <span class="font-semibold w-48">Payment</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 font-bold text-left">
                                        {{ 'IDR ' + new Intl.NumberFormat('id-ID').format(request.payment) }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-4">
                                    <span class="font-semibold w-48">Budget</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 font-bold text-gray-800 text-2xl text-left">
                                        {{ request.budget ? 'IDR ' + new Intl.NumberFormat('id-ID').format(request.budget) : '-' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-4" v-if="type === 'contractor'">
                                    <span class="font-semibold w-48">Province</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 text-left">{{ request.province }}</span>
                                </div>
                                <div class="flex items-center gap-4" v-if="type === 'contractor'">
                                    <span class="font-semibold w-48">City</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 text-left">{{ request.city }}</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="font-semibold w-48">Land Size</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 text-left">{{ request.land_size }} m²</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="font-semibold w-48">Land Shape</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 text-left">{{ request.land_shape }}</span>
                                </div>
                                <div class="flex items-center gap-4" v-if="type === 'designer'">
                                    <span class="font-semibold w-48">Sun Orientation</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 text-left">{{ request.sun_orientation }}</span>
                                </div>
                                <div class="flex items-center gap-4" v-if="type === 'designer'">
                                    <span class="font-semibold w-48">Wind Orientation</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 text-left">{{ request.wind_orientation }}</span>
                                </div>
                                <div class="flex items-center gap-4" v-if="type === 'designer' && request.design_reference_path">
                                    <span class="font-semibold w-48">Design Reference</span>
                                    <span class="flex-1">
                                        <a :href="'/storage/' + request.design_reference_path" target="_blank" class="bg-gray-100 rounded-lg px-4 py-2 text-blue-600 underline block text-left">View File</a>
                                    </span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="font-semibold w-48">Deadline</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 text-left">{{ request.deadline || '-' }}</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="font-semibold w-48">Notes</span>
                                    <span class="flex-1 bg-gray-100 rounded-lg px-4 py-2 text-left">{{ request.notes || '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div v-if="error" class="text-red-600 mb-2 text-lg">{{ error }}</div>
                        <div class="flex gap-3 w-full">
                            <button
                                class="flex-1 px-4 py-3 rounded bg-green-600 text-white hover:bg-green-700 transition text-xl font-semibold mt-8 mb-2"
                                :disabled="loading"
                                @click="updateStatus('accepted')"
                                v-if="request.status === 'waiting'"
                            >
                                Accept
                            </button>
                            <button
                                class="flex-1 px-4 py-3 rounded bg-red-600 text-white hover:bg-red-700 transition text-xl font-semibold mt-8 mb-2"
                                :disabled="loading"
                                @click="updateStatus('rejected')"
                                v-if="request.status === 'waiting'"
                            >
                                Reject
                            </button>
                            <!-- Form Purchased Design -->
<div
    v-if="type === 'designer' && request.progress === 'design_start' && request.open_acc"
    class="w-full mb-2"
>
    <div class="bg-white rounded-2xl p-6 mt-6">
        <h3 class="text-2xl font-bold mb-6 text-[#AE7A42]">Input Design to Client</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Design Name</label>
                <input 
                    v-model="purchasedForm.design_name" 
                    type="text" 
                    class="w-full px-4 py-2 border rounded-lg bg-gray-100" 
                    placeholder="Contoh: Classic Luxury Living Room"
                    required
                >
                <p class="text-xs text-gray-500 mt-1">Masukkan nama desain yang jelas dan deskriptif</p>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Design Country</label>
                <input 
                    v-model="purchasedForm.design_country" 
                    type="text" 
                    class="w-full px-4 py-2 border rounded-lg bg-gray-100" 
                    placeholder="Contoh: Indonesia, Japan, Scandinavian"
                    required
                >
                <p class="text-xs text-gray-500 mt-1">Masukkan gaya atau asal negara desain</p>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Design Specialty</label>
                <input 
                    v-model="purchasedForm.design_specialty" 
                    type="text" 
                    class="w-full px-4 py-2 border rounded-lg bg-gray-100" 
                    placeholder="Contoh: Minimalist, Industrial, Bohemian"
                    required
                >
                <p class="text-xs text-gray-500 mt-1">Spesialisasi atau gaya khusus desain</p>
            </div>
            <div>
    <label class="block text-sm font-medium mb-1">Design File (PDF/JPG/PNG, max 16MB)</label>
    <input 
        type="file"
        accept=".pdf,.jpg,.jpeg,.png"
        @change="handleFileChange"
        class="w-full px-4 py-2 border rounded-lg bg-gray-100"
        required
    >
    <p class="text-xs text-gray-500 mt-1">Upload file desain (PDF/JPG/PNG, max 16MB)</p>
</div>
            <div>
                <label class="block text-sm font-medium mb-1">Price</label>
                <input 
                    v-model="purchasedForm.price" 
                    type="number" 
                    min="0" 
                    step="0.01" 
                    class="w-full px-4 py-2 border rounded-lg bg-gray-100" 
                    placeholder="Contoh: 1500000"
                    required
                >
                <p class="text-xs text-gray-500 mt-1">Harga dalam mata uang lokal (tanpa titik/koma)</p>
            </div>
        </div>
        <div v-if="purchasedError" class="text-red-600 mt-2">{{ purchasedError }}</div>
        <div v-if="purchasedSuccess" class="text-green-600 mt-2">{{ purchasedSuccess }}</div>
        <button
            @click="submitPurchasedDesign"
            :disabled="purchasedLoading"
            class="mt-4 px-6 py-3 bg-[#AE7A42] hover:bg-[#8c5e30] text-white font-medium rounded-lg shadow transition"
        >
            <span v-if="purchasedLoading">Saving...</span>
            <span v-else>Simpan Purchased Design</span>
        </button>
    </div>
</div>

<button
    v-if="type === 'contractor' && request.progress === 'construction_start' && request.open_acc"
    class="flex-1 px-4 py-3 rounded bg-[#AE7A42] text-white hover:bg-blue-800 transition text-xl font-semibold mt-8 mb-2"
    :disabled="loading"
    @click="finishConstruction"
>
    Finish Construction
</button>
                        </div>
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
                            <template v-if="(request.progress === 'construction_start' || request.progress === 'design_start')">
        <span
            v-if="request.open_acc"
            class="px-6 py-3 bg-green-100 text-green-700 text-[20px] font-medium rounded-lg shadow flex items-center mr-4"
        >
            ACC dibuka <span class="ml-2 text-green-600 text-2xl font-bold">✓</span>
        </span>
    </template>
    <Link
        :href="`/chat/${getTargetUser()?.id}/${getClient()?.id}`"
        class="px-6 py-3 bg-[#AE7A42] hover:bg-[#8c5e30] text-white text-[20px] font-medium rounded-lg shadow transition flex items-center"
    >
        <MessageSquareText class="mr-4"/>Chat
    </Link>
                        </div>
                    </div>
                </section>
                <!-- Status Section (Mobile) -->
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
                            <template v-if="(request.progress === 'construction_start' || request.progress === 'design_start')">
        <span class="px-6 py-3 bg-green-100 text-green-700 text-[20px] font-medium rounded-lg shadow flex items-center mr-2">
            ACC dibuka <span class="ml-2 text-green-600 text-2xl font-bold">✓</span>
        </span>
    </template>
    <Link
        :href="`/chat/${getTargetUser()?.id}/${getClient()?.id}`"
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