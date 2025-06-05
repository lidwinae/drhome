<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    requests: {
        type: Array,
        required: true
    }
});

// Helper untuk label status/progress
function getStatusLabel(request: any) {
    if (request.type === 'contractor') {
        if (request.status === 'rejected') return 'Rejected';
        if (request.status === 'accepted' && request.progress === 'construction_end') return 'Finished';
        if (request.status === 'accepted' && request.progress === 'construction_start') return 'Construction Started';
        if (request.status === 'accepted' && request.progress === 'payment') return 'Waiting Payment';
        if (request.status === 'accepted' && request.progress === 'design_submitted') return 'Design Submitted';
        if (request.status === 'waiting') return 'Waiting';
        if (request.status === 'finished') return 'Finished';
    } else if (request.type === 'designer') {
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

function getStatusClass(request: any) {
    if (request.status === 'rejected') {
        return 'bg-red-100 text-red-800';
    }
    if (
        request.status === 'finished' ||
        (request.type === 'contractor' && request.progress === 'construction_end') ||
        (request.type === 'designer' && request.progress === 'design_end')
    ) {
        return 'bg-green-100 text-green-800';
    }
    if (
        (request.type === 'contractor' && ['design_submitted', 'payment', 'construction_start'].includes(request.progress)) ||
        (request.type === 'designer' && ['form_submitted', 'payment', 'design_start'].includes(request.progress))
    ) {
        return 'bg-yellow-100 text-yellow-800';
    }
    return 'bg-gray-100 text-gray-800';
}

// Helper untuk avatar dan nama tujuan
function getTargetUser(request: any) {
    if (request.type === 'contractor') {
        return request.contractor;
    } else if (request.type === 'designer') {
        return request.designer;
    }
    return null;
}
</script>

<template>
    <Head title="My Requests" />
    <AppLayout>
        <main class="bg-gray-50 w-full min-h-screen p-4 md:p-8">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">My Requests</h1>
                <div v-if="requests.length === 0" class="text-center text-gray-500 py-12">
                    No requests found
                </div>
                <div class="flex flex-col gap-4">
                    <div
                        v-for="request in requests"
                        :key="`${request.type}-${request.id}`"
                        class="bg-white rounded-xl shadow p-6 flex flex-col sm:flex-row items-center gap-4"
                    >
                        <img
                            class="h-16 w-16 rounded-full object-cover border"
                            :src="getTargetUser(request)?.avatar ? '/storage/' + getTargetUser(request).avatar : 'https://ui-avatars.com/api/?name=' + (getTargetUser(request)?.name || 'User')"
                            :alt="getTargetUser(request)?.name"
                        />
                        <div class="flex-1 w-full sm:ml-2">
                            <div class="flex items-center gap-2">
                                <div class="font-semibold text-lg text-gray-900">{{ getTargetUser(request)?.name }}</div>
                                <span class="ml-2 px-2 py-0.5 rounded text-xs font-semibold"
                                    :class="request.type === 'contractor' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'">
                                    To {{ request.type === 'contractor' ? 'Contractor' : 'Designer' }}
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2 text-sm text-gray-700 mt-1">
                                <span>Budget: <b>{{ request.budget ? 'IDR ' + new Intl.NumberFormat('id-ID').format(request.budget) : '-' }}</b></span>
                                <span v-if="request.type === 'contractor'">
                                    Province: <b>{{ request.province }}</b>
                                </span>
                                <span v-if="request.type === 'contractor'">
                                    City: <b>{{ request.city }}</b>
                                </span>
                                <span>
                                    Land Size: <b>{{ request.land_size }}</b> m²
                                </span>
                                <span>
                                    Land Shape: <b>{{ request.land_shape }}</b>
                                </span>
                                <span v-if="request.type === 'designer'">
                                    Sun Orientation: <b>{{ request.sun_orientation }}</b>
                                </span>
                                <span v-if="request.type === 'designer'">
                                    Wind Orientation: <b>{{ request.wind_orientation }}</b>
                                </span>
                            </div>
                            <div class="mt-1">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    :class="getStatusClass(request)"
                                >
                                    {{ getStatusLabel(request) }}
                                </span>
                            </div>
                        </div>
                        <div class="w-full sm:w-auto flex justify-end">
                            <Link
                                :href="request.type === 'contractor' ? `/myrequest/${request.id}` : `/myrequest/${request.id}`"
                                class="mt-2 sm:mt-0 px-4 py-2 rounded bg-[#AE7A42] text-white hover:bg-[#8c5e30] transition"
                            >
                                View Details
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </AppLayout>
</template>