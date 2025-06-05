<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

const statuses = [
    { id: 'fill_form', label: 'Fill Form' },
    { id: 'design_submitted', label: 'Design Submitted' },
    { id: 'payment', label: 'Payment' },
    { id: 'construction_start', label: 'Construction Start' },
    { id: 'construction_end', label: 'Construction End' }
];

const currentStatus = ref('fill_form');
const purchasedDesigns = ref<Array<any>>([]);
const selectedDesign = ref('');
const province = ref('');
const city = ref('');
const landSize = ref('');
const landShape = ref('');
const budget = ref('');
const deadline = ref('');
const notes = ref('');
const loading = ref(false);
const error = ref<string|null>(null);
const success = ref<string|null>(null);

const page = usePage();
const contractorId = page.props.contractorId;

onMounted(async () => {
    try {
        const res = await axios.get('/api/user/purchased-designs');
        purchasedDesigns.value = res.data.designs;
    } catch (err) {
        console.error(err);
        purchasedDesigns.value = [];
    }
});

async function submitRequest() {
    error.value = null;
    success.value = null;

    if (!selectedDesign.value || !province.value || !city.value || !landSize.value || !landShape.value || !notes.value) {
        error.value = 'Semua field wajib diisi!';
        return;
    }

    loading.value = true;
    try {
        await axios.post(`/api/contractors/${contractorId}/request`, {
            purchased_design_id: selectedDesign.value,
            province: province.value,
            city: city.value,
            land_size: landSize.value,
            land_shape: landShape.value,
            budget: budget.value ? parseFloat(budget.value) : null,
            deadline: deadline.value ? deadline.value : null,
            notes: notes.value,
        });
        window.location.href = '/myrequest';
    } catch (err: any) {
        error.value = err.response?.data?.message || 'Gagal mengirim request.';
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <Head title="Request Contractor" />
    <AppLayout>
        <main class="bg-gray-50 w-full min-h-screen p-4 md:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                <section class="flex flex-col lg:flex-row gap-6">
                    <div class="house-design bg-white rounded-2xl shadow-md p-6 lg:w-2/3">
                        <div class="space-y-6">
                            <h2 class="text-2xl font-bold text-gray-800 border-b pb-3">Request Contractor</h2>
                            <div class="mb-4 text-sm text-[#AE7A42] bg-[#FFF7ED] border border-[#AE7A42] rounded-lg px-4 py-3">
                                <span class="font-semibold">*</span> Anda harus sudah memiliki design yang sudah dibeli jika ingin request contractor.
                            </div>
                            <div v-if="error" class="mb-2 text-red-600">{{ error }}</div>
                            <div v-if="success" class="mb-2 text-green-600">{{ success }}</div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700">Province</label>
                                    <input v-model="province" type="text" placeholder="Enter Province"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700">City</label>
                                    <input v-model="city" type="text" placeholder="Enter City"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700">Land Size (m²)</label>
                                    <input v-model="landSize" type="text" placeholder="Enter Land Size"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700">Land Shape</label>
                                    <select v-model="landShape" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                        <option value="">Select Land Shape</option>
                                        <option value="rectangle">Rectangle</option>
                                        <option value="square">Square</option>
                                        <option value="irregular">Irregular</option>
                                    </select>
                                </div>
                                <div class="space-y-1 md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Design File</label>
                                    <div class="flex items-center gap-3">
                                        <select v-model="selectedDesign" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                            <option value="">Pilih Design yang sudah dibeli</option>
                                            <option v-for="item in purchasedDesigns" :key="item.id" :value="item.id">
                                                {{ item.design_name }} - {{ item.design_country }} - {{ item.design_specialty }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="space-y-1 md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Budget (IDR)</label>
                                    <input v-model="budget" type="number" min="0" placeholder="Enter Budget"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                </div>
                                <div class="space-y-1 md:col-span-2 mb-2">
                                    <label class="block text-sm font-medium text-gray-700">Deadline</label>
                                    <input v-model="deadline" type="date"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                </div>
                                <div class="space-y-1 md:col-span-2 mb-2">
                                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                                    <textarea
                                    v-model="notes"
                                    id="message"
                                    rows="4"
                                    placeholder="Write notes or more information here..."
                                    class="w-full px-4 py-4 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition"
                                    required
                                    ></textarea>
                                        
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Status Section (Desktop) -->
                    <section class="status-section bg-[#AE7A42] rounded-2xl shadow-md p-6 lg:w-1/3 flex flex-col justify-center hidden lg:block">
                        <h2 class="text-2xl font-bold text-white border-b border-[#fff7ed]/30 pb-3 mb-6">Project Status</h2>
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
                </section>
                
                <!-- Status Section (Mobile) -->
                <section class="status-section bg-[#AE7A42] rounded-2xl shadow-md p-6 block lg:hidden">
                    <h2 class="text-2xl font-bold text-white border-b border-[#fff7ed]/30 pb-3 mb-6">Project Status</h2>
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
                <!-- Footer -->
                <div class="flex justify-end">
                    <button
                        class="px-6 py-3 bg-[#AE7A42] hover:bg-[#8c5e30] text-white font-medium rounded-lg shadow transition"
                        :disabled="loading"
                        @click="submitRequest"
                    >
                        <span v-if="loading">Submitting...</span>
                        <span v-else>Submit Request</span>
                    </button>
                </div>
            </div>
        </main>
    </AppLayout>
</template>