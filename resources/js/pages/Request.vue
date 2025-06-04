<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    requests: {
        type: Array,
        required: true
    },
    role: {
        type: String,
        required: true
    }
});
</script>

<template>
    <Head title="Request" />
    <AppLayout>
        <main class="bg-gray-50 w-full min-h-screen p-4 md:p-8">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Your Request Job</h1>
                <div v-if="requests.length === 0" class="text-center text-gray-500 py-12">
                    No requests found
                </div>
                <div class="flex flex-col gap-4">
                    <div v-for="request in requests" :key="request.id" class="bg-white rounded-xl shadow p-4 flex flex-col sm:flex-row items-center gap-4">
                        <img
                            class="h-16 w-16 rounded-full object-cover border"
                            :src="request.client?.avatar ? '/storage/' + request.client.avatar : 'https://ui-avatars.com/api/?name=' + request.client?.name"
                            :alt="request.client?.name"
                        />
                        <div class="flex-1 w-full sm:ml-2">
                            <div class="font-semibold text-lg text-gray-900">{{ request.client?.name }}</div>
                            <div class="text-sm text-gray-600 mb-1">{{ request.city }}, {{ request.province }}</div>
                            <div class="flex flex-wrap gap-2 text-sm text-gray-700">
                                <span>Land Size: <b>{{ request.land_size }} m²</b></span>
                                <span>Budget: <b>{{ request.budget ? 'IDR ' + new Intl.NumberFormat('id-ID').format(request.budget) : '-' }}</b></span>
                            </div>
                            <div class="mt-1">
                                <span :class="{
                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                    'bg-green-100 text-green-800': request.status === 'request_accepted' || request.status === 'construction_end',
                                    'bg-yellow-100 text-yellow-800': request.status === 'design_submitted' || request.status === 'payment' || request.status === 'construction_start',
                                    'bg-red-100 text-red-800': request.status === 'rejected'
                                }">
                                    {{ request.status.replace('_', ' ') }}
                                </span>
                            </div>
                        </div>
                        <div class="w-full sm:w-auto flex justify-end">
                            <Link :href="`/request/${request.id}`" class="mt-2 sm:mt-0 px-4 py-2 rounded bg-[#AE7A42] text-white hover:bg-[#8c5e30] transition">
                                View Details
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </AppLayout>
</template>