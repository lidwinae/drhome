<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Update Role',
        href: '/admin/add',
    },
];

const form = useForm({
    email: '',
    role: 'designer',
});

const clients = ref([]);
const loadingClients = ref(true);

onMounted(async () => {
    try {
        const response = await axios.get('/api/admin/clients');
        clients.value = response.data;
    } catch (error) {
        console.error('Failed to fetch clients:', error);
    } finally {
        loadingClients.value = false;
    }
});

const submit = () => {
    form.patch(route('upd'), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Update User Role" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- Container utama dengan padding responsif -->
        <div class="mx-auto w-full px-4 py-6 sm:px-6 lg:px-12">
            <!-- Card container - lebar penuh di mobile, lebih lebar di desktop -->
            <div class="mx-auto w-full max-w-6xl">
                <div class="flex justify-between items-center mb-6 mt-2 ml-1">
                <h2 class="text-2xl font-archivo font-semibold">Role Management</h2>
            </div>
                <!-- Card dengan shadow dan border yang lebih halus -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                    <!-- Padding yang lebih responsif -->
                    <div class="px-5 py-6 sm:p-8">
                        <!-- Deskripsi dengan ukuran 18px (text-[18px]) -->
                        <div class="mt-2 max-w-3xl text-[17px] sm:text-[17px] text-gray-600">
                            <p>Tambahkan designer atau kontraktor baru dengan mengupdate role user</p>
                        </div>
                        
                        <!-- Form dengan layout responsif -->
                        <form @submit.prevent="submit" class="mt-6">
                            <!-- Grid layout yang beradaptasi dengan layar -->
                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                                <!-- Email dropdown - mengambil 2 kolom di md+ -->
                                <div class="md:col-span-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Client
                                    </label>
                                    <select
                                        v-model="form.email"
                                        id="email"
                                        class="block w-full rounded-lg border-0 py-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-500 text-base sm:text-sm px-4"
                                        required
                                        :disabled="loadingClients"
                                    >
                                        <option value="" disabled selected>Pilih Client</option>
                                        <option 
                                            v-for="client in clients" 
                                            :key="client.id" 
                                            :value="client.email"
                                        >
                                            {{ client.name }} ({{ client.email }})
                                        </option>
                                    </select>
                                    <p v-if="loadingClients" class="mt-2 text-sm text-gray-500">
                                        Memuat daftar client...
                                    </p>
                                </div>

                                <!-- Role select -->
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                        Role Baru
                                    </label>
                                    <select
                                        v-model="form.role"
                                        id="role"
                                        class="block w-full rounded-lg border-0 py-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-500 text-base sm:text-sm px-4"
                                    >
                                        <option value="designer">Designer</option>
                                        <option value="contractor">Contractor</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Submit button dengan margin yang lebih baik -->
                            <div class="mt-8 sm:mt-10 flex justify-end">
                                <button
                                    type="submit"
                                    class="w-full sm:w-auto px-8 py-3 rounded-lg bg-[#AE7A42] text-[17px] font-medium text-white shadow-sm hover:bg-[#9c6d3a] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#AE7A42] transition-colors disabled:opacity-50"
                                    :disabled="form.processing || loadingClients"
                                >
                                    <span v-if="form.processing">Memproses...</span>
                                    <span v-else>Update Role</span>
                                </button>
                            </div>
                        </form>

                        <!-- Status messages dengan animasi -->
                        <transition name="fade">
                            <div v-if="form.recentlySuccessful" class="mt-6 p-4 bg-green-50 text-green-700 rounded-lg text-sm border border-green-100">
                                ✓ Role berhasil diupdate!
                            </div>
                        </transition>
                        
                        <transition name="fade">
                            <div v-if="form.errors.email" class="mt-6 p-4 bg-red-50 text-red-700 rounded-lg text-sm border border-red-100">
                                {{ form.errors.email }}
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Animasi untuk status messages */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

/* Perbaikan responsivitas untuk select */
select {
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1.2em;
}

/* Padding lebih baik di mobile */
@media (max-width: 640px) {
    .px-5 {
        padding-left: 1.25rem;
        padding-right: 1.25rem;
    }
}
</style>