<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import VuePdfEmbed from 'vue-pdf-embed'

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
const contractors = ref([]);
const loadingContractors = ref(true);

onMounted(async () => {
    try {
        const [clientsResponse, contractorsResponse] = await Promise.all([
            axios.get('/api/admin/clients'),
            axios.get('/api/admin/contractors')
        ]);
        clients.value = clientsResponse.data;
        contractors.value = contractorsResponse.data;
    } catch (error) {
        console.error('Failed to fetch data:', error);
    } finally {
        loadingClients.value = false;
        loadingContractors.value = false;
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
                
                <!-- Card Update Role -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden mb-8">
                    <div class="px-5 py-6 sm:p-8">
                        <div class="mt-2 max-w-3xl text-[17px] sm:text-[17px] text-gray-600">
                            <p>Tambahkan designer atau kontraktor baru dengan mengupdate role user</p>
                        </div>
                        
                        <form @submit.prevent="submit" class="mt-6">
                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
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

                <!-- Card Contractors List -->
<div class="mb-8">
    <h2 class="text-xl font-archivo font-semibold mb-6 px-2">Contractors List</h2>
    
    <div v-if="loadingContractors" class="text-center py-8">
        <p class="text-gray-500">Memuat data contractors...</p>
    </div>
    
    <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div 
            v-for="contractor in contractors" 
            :key="contractor.id"
            class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow flex flex-col lg:flex-row"
        >
            <!-- Bagian Kiri (Portfolio) -->
            <div v-if="contractor.portfolio" class="lg:w-1/3 mb-4 lg:mb-0 lg:pr-4">
                <p class="font-semibold text-sm mb-2 lg:hidden">Portfolio:</p>
                <div class="border border-gray-300 rounded-md overflow-hidden h-48 lg:h-full">
                    <vue-pdf-embed
                        :source="`data:application/pdf;base64,${contractor.portfolio}`"
                        class="h-full w-full"
                        :page="1"
                    />
                </div>
            </div>
            <div v-else class="lg:w-1/3 mr-4 mb-4 lg:mb-0 lg:pr-4 flex items-center justify-center bg-gray-100 rounded-md">
                <p class="text-sm text-gray-500 p-4">Tidak ada portfolio</p>
            </div>
                        <!-- Kolom 2/3: Label + Data -->
            <div class="lg:w-2/3 flex">
                <!-- Kolom 1/3: Label -->
                <div class="w-1/2 lg:w-1/3 pr-2">
                    <div class="space-y-3 text-left font-semibold text-gray-900">
                        <p>Name</p>
                        <p>Email</p>
                        <p>Specialty</p>
                    </div>
                </div>
                
                <!-- Kolom 1/3: Data -->
                <div class="w-1/2 lg:w-2/3">
                    <div class="space-y-3 text-gray-600">
                        <p>: {{ contractor.name }}</p>
                        <p>: {{ contractor.email }}</p>
                        <p>: {{ contractor.specialty }}</p>
                    </div>
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
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

select {
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1.2em;
}

@media (max-width: 640px) {
    .px-5 {
        padding-left: 1.25rem;
        padding-right: 1.25rem;
    }
}

.pdf-viewer {
    height: 100%;
    width: 300px;
    border: none;
}

/* Untuk mobile */
@media (max-width: 640px) {
    .pdf-viewer {
        height: 150px;
    }
}
</style>