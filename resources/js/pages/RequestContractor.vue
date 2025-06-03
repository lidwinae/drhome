<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const statuses = [
    { id: 'design_submitted', label: 'Design Submitted' },
    { id: 'request_accepted', label: 'Request Accepted' },
    { id: 'payment', label: 'Payment' },
    { id: 'construction_start', label: 'Construction Start' },
    { id: 'construction_end', label: 'Construction End' }
];

const currentStatus = ref('design_submitted');
</script>

<template>
    <Head title="Request Contractor" />
    <AppLayout>
        <main class="bg-gray-50 w-full min-h-screen p-4 md:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                <!-- Request Contractor -->
                <section class="flex flex-col lg:flex-row gap-6">
                    <div class="house-design bg-white rounded-2xl shadow-md p-6 lg:w-2/3">
                        <div class="space-y-6">
                            <h2 class="text-2xl font-bold text-gray-800 border-b pb-3">Request Contractor</h2>
                            <div class="mb-4 text-sm text-[#AE7A42] bg-[#FFF7ED] border border-[#AE7A42] rounded-lg px-4 py-3">
                                <span class="font-semibold">*</span> Anda harus sudah memiliki file design terlampir jika ingin request contractor.
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700">Province</label>
                                    <input type="text" placeholder="Enter Province"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" placeholder="Enter City"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700">Land Size (m²)</label>
                                    <input type="text" placeholder="Enter Land Size"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700">Land Shape</label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                        <option value="">Select Land Shape</option>
                                        <option value="rectangle">Rectangle</option>
                                        <option value="square">Square</option>
                                        <option value="irregular">Irregular</option>
                                    </select>
                                </div>
                                <div class="space-y-1 md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Design File</label>
                                    <div class="flex items-center gap-3">
                                        <input type="file" class="hidden" id="preview-picture">
                                        <label for="preview-picture" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg cursor-pointer transition">
                                            Choose File
                                        </label>
                                        <span class="text-sm text-gray-500">No file chosen</span>
                                    </div>
                                </div>
                                <div class="space-y-1 md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Budget (IDR)</label>
                                    <input type="text" placeholder="Enter Budget"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Timeline Section (Desktop) -->
                    <section class="timeline-section bg-white rounded-2xl shadow-md p-6 lg:w-1/3 hidden lg:block">
                        <h2 class="text-2xl font-bold text-gray-800 border-b pb-3 mb-6">Timeline</h2>
                        <div class="space-y-1 max-w-md">
                            <label class="block text-sm font-medium text-gray-700">Deadline</label>
                            <div class="relative">
                                <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition pr-10">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Status Section (Desktop) -->
                    <section class="status-section bg-[#AE7A42] rounded-2xl shadow-md p-6 lg:w-1/3 flex flex-col justify-center hidden lg:block">
                        <h2 class="text-2xl font-bold text-white border-b border-[#fff7ed]/30 pb-3 mb-6">Project Status</h2>
                        <div class="space-y-4">
                            <div v-for="(status, index) in statuses" :key="status.id" class="flex items-start">
                                <div class="flex-shrink-0 relative">
                                    <!-- Status circle -->
                                    <div :class="[
                                        'h-6 w-6 rounded-full flex items-center justify-center border-2',
                                        currentStatus === status.id
                                            ? 'bg-white text-[#AE7A42] border-white'
                                            : 'bg-[#AE7A42]/30 text-white border-white/30'
                                    ]">
                                        <span v-if="currentStatus === status.id">✓</span>
                                    </div>
                                    <!-- Vertical line between statuses -->
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
                <!-- Timeline Section (Mobile) -->
                <section class="timeline-section bg-white rounded-2xl shadow-md p-6 block lg:hidden">
                    <h2 class="text-2xl font-bold text-gray-800 border-b pb-3 mb-6">Timeline</h2>
                    <div class="space-y-1 max-w-md">
                        <label class="block text-sm font-medium text-gray-700">Deadline</label>
                        <div class="relative">
                            <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition pr-10">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Status Section (Mobile) -->
                <section class="status-section bg-[#AE7A42] rounded-2xl shadow-md p-6 block lg:hidden">
                    <h2 class="text-2xl font-bold text-white border-b border-[#fff7ed]/30 pb-3 mb-6">Project Status</h2>
                    <div class="space-y-4">
                        <div v-for="(status, index) in statuses" :key="status.id" class="flex items-start">
                            <div class="flex-shrink-0 relative">
                                <!-- Status circle -->
                                <div :class="[
                                    'h-6 w-6 rounded-full flex items-center justify-center border-2',
                                    currentStatus === status.id
                                        ? 'bg-white text-[#AE7A42] border-white'
                                        : 'bg-[#AE7A42]/30 text-white border-white/30'
                                ]">
                                    <span v-if="currentStatus === status.id">✓</span>
                                </div>
                                <!-- Vertical line between statuses -->
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
                    <button class="px-6 py-3 bg-[#AE7A42] hover:bg-[#8c5e30] text-white font-medium rounded-lg shadow transition">
                        Submit Request
                    </button>
                </div>
            </div>
        </main>
    </AppLayout>
</template>