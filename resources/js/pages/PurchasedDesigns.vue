<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const purchased = ref<Array<any>>([]);
const isLoading = ref(true);
const error = ref<string | null>(null);

async function fetchPurchased() {
    isLoading.value = true;
    try {
        const response = await axios.get('/api/purchaseddesigns');
        purchased.value = response.data.data || [];
        error.value = null;
    } catch (err: any) {
        error.value = err?.response?.data?.message || 'Failed to load purchased designs';
        purchased.value = [];
    } finally {
        isLoading.value = false;
    }
}

onMounted(() => {
    fetchPurchased();
});
</script>

<template>
    <Head title="Purchased Designs" />
    <AppLayout>
        <main class="bg-gray-50 w-full min-h-screen p-4 md:p-8">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">My Purchased Designs</h1>
                <div v-if="isLoading" class="flex flex-col items-center justify-center py-8 text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-[#AE7A42] mb-4"></div>
                    <p class="text-black mt-2">Loading...</p>
                </div>
                <div v-else-if="error" class="text-center text-red-500 py-12">
                    {{ error }}
                </div>
                <div v-else-if="purchased.length === 0" class="text-center text-gray-500 py-12">
                    No purchased designs found
                </div>
                <div v-else class="flex flex-col gap-4">
                    <p class="text-[16px] text-gray-800">* Anda hanya dapat menambahkan design di sini dengan membeli dari designer atau membeli dari halaman Design. View File hanya tersedia saat Anda melakukan request ke designer / contractor.</p>
                    <div
                        v-for="item in purchased"
                        :key="item.id"
                        class="bg-white rounded-xl shadow p-6 flex flex-col sm:flex-row items-center gap-4 border border-black/40"
                    >
                        <template v-if="item.design_id && item.photo_url">
                            <img
                                class="h-16 w-16 rounded object-cover border"
                                :src="item.photo_url"
                                :alt="item.design_name"
                            />
                        </template>
                        <div class="flex-1 w-full sm:ml-2">
                            <div class="font-semibold text-lg text-gray-900">{{ item.design_name }}</div>
                            <div class="flex flex-wrap gap-2 text-sm text-gray-700">
                                <span>Country: <b>{{ item.design_country }}</b></span>
                                <span>Specialty: <b>{{ item.design_specialty }}</b></span>
                                <span>Price: <b>IDR {{ new Intl.NumberFormat('id-ID').format(item.price) }}</b></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </AppLayout>
</template>