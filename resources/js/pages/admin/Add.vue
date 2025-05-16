<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Update Role', href: '/admin/add' },
];

// Form untuk update role
const updateRoleForm = useForm({
    email: '',
    role: 'designer',
});

// Form untuk update specialty dan portfolio
const updateSpecialtyForm = useForm({
    user_id: '',
    specialty: '',
    portfolio: null as File | null
});

// State untuk UI
const pdfPreview = ref('');
const clients = ref([]);
const isFetchingClients = ref(true);
const existingPortfolioUrl = ref('');

// Fetch client users
onMounted(async () => {
    await fetchClients();
});

const fetchClients = async () => {
    isFetchingClients.value = true;
    try {
        const response = await axios.get('/api/admin/users');
        clients.value = response.data.filter(user => user.role === 'contractor' || user.role === 'designer');
    } catch (error) {
        console.error('Error fetching clients:', error);
    } finally {
        isFetchingClients.value = false;
    }
};

// Handle file change
const handleFileChange = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files[0]) {
        updateSpecialtyForm.portfolio = input.files[0];
        
        // Create PDF preview
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = (e) => {
            pdfPreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

// Watch for user selection change
watch(() => updateSpecialtyForm.user_id, async (newUserId) => {
    if (!newUserId) {
        existingPortfolioUrl.value = '';
        return;
    }
    
    try {
        // Cek role user untuk menentukan endpoint API
        const user = clients.value.find(u => u.id == newUserId);
        if (!user) return;

        const endpoint = user.role === 'contractor' 
            ? `/api/contractors/${newUserId}`
            : `/api/designers/${newUserId}`;

        const response = await axios.get(endpoint);
        if (response.data) {
            updateSpecialtyForm.specialty = response.data.specialty;
            existingPortfolioUrl.value = response.data.portfolio_url || '';
        }
    } catch (error) {
        console.error('Error fetching user data:', error);
        existingPortfolioUrl.value = '';
    }
});

// Submit update role (card atas)
const submitUpdateRole = () => {
    updateRoleForm.patch(route('upd'), {
        onSuccess: () => {
            updateRoleForm.reset();
            fetchClients(); // Refresh client list
        },
    });
};

// Submit update specialty/portfolio (card bawah)
const submitUpdateSpecialty = async () => {
    if (!updateSpecialtyForm.user_id) {
        alert('Pilih user terlebih dahulu');
        return;
    }

    const formData = new FormData();
    formData.append('specialty', updateSpecialtyForm.specialty);
    
    if (updateSpecialtyForm.portfolio) {
        formData.append('portfolio', updateSpecialtyForm.portfolio);
    }

    try {
        const response = await axios.patch(
            `/admin/add/${updateSpecialtyForm.user_id}`,
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        );

        // Handle success
        alert(response.data.success);
        // Reset form atau lakukan refresh data
    } catch (error) {
        console.error('Error:', error.response?.data?.error || error.message);
        alert(error.response?.data?.error || 'Gagal mengupdate data');
    }
};
</script>

<template>
    <Head title="Update User Role" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <!-- Form Update Role -->
            <div class="mx-auto max-w-4xl">
                <div class="bg-white shadow-md sm:rounded-lg sm:mr-4 border-black-400 border-2 mb-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="font-archivo font-semibold text-[20px] text-black">Update User Role</h2>
                        <div class="mt-2 max-w-xl text-[15px] text-gray-500">
                            <p>Tambahkan designer / kontraktor baru dengan update role user</p>
                        </div>
                        <form @submit.prevent="submitUpdateRole" class="mt-5">
                            <div class="flex flex-col sm:flex-row gap-4 items-end">
                                <div class="w-full sm:w-96">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input
                                        v-model="updateRoleForm.email"
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                                        placeholder="email@example.com"
                                        required
                                    />
                                </div>
                                <div class="w-full sm:w-64">
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                    <select
                                        v-model="updateRoleForm.role"
                                        id="role"
                                        name="role"
                                        class="block w-full rounded-md border-0 py-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                                    >
                                        <option value="designer">Designer</option>
                                        <option value="contractor">Contractor</option>
                                    </select>
                                </div>
                                <button
                                    type="submit"
                                    class="w-full sm:w-48 h-11 flex items-center justify-center rounded-md bg-[#AE7A42] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#9c6d3a] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#AE7A42] transition-colors"
                                    :disabled="updateRoleForm.processing"
                                >
                                    <span v-if="updateRoleForm.processing">Processing...</span>
                                    <span v-else>Update Role</span>
                                </button>
                            </div>
                        </form>
                        <div v-if="updateRoleForm.recentlySuccessful" class="mt-3 text-sm text-green-600">
                            Role berhasil diupdate!
                        </div>
                        <div v-if="updateRoleForm.errors.email" class="mt-3 text-sm text-red-600">
                            {{ updateRoleForm.errors.email }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Update Specialty dan Portfolio -->
            <div class="mx-auto max-w-4xl">
                <div class="bg-white shadow-md sm:rounded-lg sm:mr-4 border-black-400 border-2">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="font-archivo font-semibold text-[20px] text-black">
                            Update Specialty dan Portfolio
                        </h2>
                        <div class="mt-2 max-w-xl text-[15px] text-gray-500">
                            <p>Update data specialty dan portfolio</p>
                        </div>
                        <form @submit.prevent="submitUpdateSpecialty" class="mt-5 space-y-4">
                            <!-- Client Selection -->
                            <div>
                                <label for="client" class="block text-sm font-medium text-gray-700 mb-1">Pilih Client</label>
                                <select
                                    v-model="updateSpecialtyForm.user_id"
                                    id="client"
                                    class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                                    required
                                >
                                    <option value="">Pilih Client</option>
                                    <option 
                                        v-for="client in clients" 
                                        :key="client.id" 
                                        :value="client.id"
                                    >
                                        {{ client.name }} ({{ client.email }}) - {{ client.role }}
                                    </option>
                                </select>
                                <div v-if="!isFetchingClients && clients.length === 0" class="text-sm text-red-600 mt-1">
                                    Tidak ada data client yang tersedia.
                                </div>
                            </div>

                            <!-- Specialty -->
                            <div>
                                <label for="specialty" class="block text-sm font-medium text-gray-700 mb-1">Specialty</label>
                                <input
                                    v-model="updateSpecialtyForm.specialty"
                                    type="text"
                                    id="specialty"
                                    class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                                    placeholder="e.g. Medieval Architecture"
                                    maxlength="50"
                                />
                            </div>

                            <!-- PDF Upload -->
                            <div>
                                <label for="portfolio" class="block text-sm font-medium text-gray-700 mb-1">
                                    Portfolio (PDF, max 16MB) (Opsional)
                                </label>
                                <input
                                    type="file"
                                    id="portfolio"
                                    name="portfolio"
                                    @change="handleFileChange"
                                    accept="application/pdf"
                                    class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-[#AE7A42] file:text-white
                                        hover:file:bg-[#9c6d3a]"
                                />
                                <div v-if="existingPortfolioUrl" class="text-sm text-gray-500 mt-1">
                                    File saat ini: <a :href="existingPortfolioUrl" target="_blank" class="text-[#AE7A42] hover:underline">Lihat Portfolio</a>
                                </div>
                            </div>

                            <!-- PDF Preview -->
                            <div v-if="pdfPreview" class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">PDF Preview</label>
                                <iframe 
                                    :src="pdfPreview" 
                                    class="w-full h-96 border border-gray-300 rounded-md"
                                    frameborder="0">
                                </iframe>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-2">
                                <button
                                    type="submit"
                                    class="w-full sm:w-48 h-11 flex items-center justify-center rounded-md bg-[#AE7A42] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#9c6d3a] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#AE7A42] transition-colors"
                                    :disabled="updateSpecialtyForm.processing"
                                >
                                    <span v-if="updateSpecialtyForm.processing">Processing...</span>
                                    <span v-else>Update Data</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>