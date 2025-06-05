<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const breadcrumbs = [
    { title: 'Update Role', href: '/admin/add/contractor' },
];

const clients = ref([]);
const loadingClients = ref(true);
const contractors = ref([]);
const loadingContractors = ref(true);

const showModal = ref(false);
const selectedContractor = ref<any>(null);
const loadingContractorDetails = ref(false);

// Edit portfolio state
const editingPortfolio = ref(false);
const portfolioFile = ref<File|null>(null);
const uploadingPortfolio = ref(false);
const uploadError = ref('');

onMounted(async () => {
    try {
        const [clientsResponse, contractorsResponse] = await Promise.all([
            axios.get('/api/admin/clients'),
            axios.get('/api/admin/contractors')
        ]);
        clients.value = clientsResponse.data;
        contractors.value = contractorsResponse.data.data ?? contractorsResponse.data;
    } catch (error) {
        console.error('Failed to fetch data:', error);
    } finally {
        loadingClients.value = false;
        loadingContractors.value = false;
    }
});

const fetchContractors = async () => {
    loadingContractors.value = true;
    try {
        const contractorsResponse = await axios.get('/api/admin/contractors');
        contractors.value = contractorsResponse.data.data ?? contractorsResponse.data;
    } catch (error) {
        console.error('Failed to fetch contractors:', error);
    } finally {
        loadingContractors.value = false;
    }
};

const form = useForm({
    email: '',
    role: 'contractor',
});

const submit = () => {
    form.patch(route('upd'), {
        onSuccess: () => {
            form.reset();
            fetchContractors();
        },
    });
};

const openContractorModal = async (contractorId: number) => {
    try {
        loadingContractorDetails.value = true;
        const response = await axios.get(`/api/contractors/${contractorId}`);
        selectedContractor.value = response.data.data;
        showModal.value = true;
        editingPortfolio.value = false;
        portfolioFile.value = null;
        uploadError.value = '';
    } catch (error) {
        console.error('Failed to fetch contractor details:', error);
    } finally {
        loadingContractorDetails.value = false;
    }
};

const closeModal = () => {
    showModal.value = false;
    selectedContractor.value = null;
    editingPortfolio.value = false;
    portfolioFile.value = null;
    uploadError.value = '';
};

const startEditPortfolio = () => {
    editingPortfolio.value = true;
    portfolioFile.value = null;
    uploadError.value = '';
};

const handlePortfolioFile = (e: Event) => {
    const files = (e.target as HTMLInputElement).files;
    if (files && files.length > 0) {
        portfolioFile.value = files[0];
    }
};

const savePortfolio = async () => {
    if (!portfolioFile.value || !selectedContractor.value) return;
    uploadingPortfolio.value = true;
    uploadError.value = '';
    
    try {
        const formData = new FormData();
        formData.append('portfolio', portfolioFile.value);
        const response = await axios.post(`/api/contractors/${selectedContractor.value.id}/portfolio`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });

        // Refresh contractor data
        selectedContractor.value.portfolio = response.data.portfolio;
        editingPortfolio.value = false;
    } catch (err: any) {
        uploadError.value = err.response?.data?.message || 'Gagal upload portfolio';
    } finally {
        uploadingPortfolio.value = false;
    }
};
</script>

<template>
    <Head title="Update User Role" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full px-4 py-6 sm:px-6 lg:px-12">
            <div class="mx-auto w-full max-w-6xl">
                <div class="flex justify-between items-center mb-6 mt-2 ml-1">
                    <h2 class="text-2xl font-archivo font-semibold">Role Management: Contractor</h2>
                </div>
                
                <!-- Card Update Role -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden mb-8">
                    <div class="px-5 py-6 sm:p-8">
                        <div class="mt-2 max-w-3xl text-[17px] sm:text-[17px] text-gray-600">
                            <p>Tambahkan kontraktor baru dengan mengupdate role user</p>
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
                                        class="block w-full rounded-lg border-0 py-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-[#AE7A42]-500 text-base sm:text-sm px-4"
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
                                        class="block w-full rounded-lg border-0 py-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-[#AE7A42]-500 text-base sm:text-sm px-4"
                                    >
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
                            class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow flex flex-row cursor-pointer"
                            style="border-width: 1.5px;"
                            @click="openContractorModal(contractor.id)"
                        >
                            <div class="w-1/5 mr-5 flex items-center justify-center rounded-md flex-shrink-0">
                                <img v-if="contractor.avatar_url" :src="contractor.avatar_url" class="h-16 w-16 rounded-full object-cover" alt="Avatar">
                                <span v-else class="text-2xl text-gray-400">{{ contractor.name.charAt(0) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="space-y-2">
                                    <h3 class="text-lg font-medium text-gray-900 truncate break-words">{{ contractor.name }}</h3>
                                    <p class="text-gray-600 break-words"><span class="font-semibold">Specialty:</span> {{ contractor.specialty }}</p>
                                    <a
                                        v-if="contractor.portfolio_url"
                                        :href="contractor.portfolio_url"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-[#AE7A42] hover:underline text-sm font-medium"
                                        @click.prevent="openContractorModal(contractor.id)"
                                    >View Portfolio</a>
                                    <span v-else class="text-gray-400 text-sm">Belum ada portfolio</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal untuk menampilkan detail contractor -->
        <transition name="modal">
            <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                        <div class="absolute inset-0 bg-gray-500 opacity-75" @click="closeModal"></div>
                    </div>
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-left sm:mt-0 sm:text-left w-full">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                                            Contractor Details
                                        </h3>
                                        <button @click="closeModal" class="text-gray-400 hover:text-gray-500">
                                            <span class="sr-only">Close</span>
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div v-if="loadingContractorDetails" class="text-center py-8">
                                        <p class="text-gray-500">Memuat detail contractor...</p>
                                    </div>
                                    <div v-else-if="selectedContractor" class="space-y-4">
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0">
                                                <img 
                                                    v-if="selectedContractor.avatar_url" 
                                                    :src="selectedContractor.avatar_url" 
                                                    class="h-16 w-16 rounded-full object-cover"
                                                    alt="Avatar"
                                                >
                                                <div v-else class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <span class="text-gray-500 text-xl">{{ selectedContractor.name.charAt(0) }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-semibold">{{ selectedContractor.name }}</h4>
                                                <p class="text-gray-600">{{ selectedContractor.email }}</p>
                                                <p class="text-sm text-gray-500">
                                                    {{ selectedContractor.origin_city }}, {{ selectedContractor.country }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-sm font-medium text-gray-500">Specialty</p>
                                                <p class="mt-1 text-sm text-gray-900">{{ selectedContractor.specialty || '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-500">Role</p>
                                                <p class="mt-1 text-sm text-gray-900 capitalize">{{ selectedContractor.role }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-500">Member Since</p>
                                                <p class="mt-1 text-sm text-gray-900">
                                                    {{ new Date(selectedContractor.created_at).toLocaleDateString('id-ID', { 
                                                        year: 'numeric', 
                                                        month: 'long', 
                                                        day: 'numeric' 
                                                    }) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Description</p>
                                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">
                                                {{ selectedContractor.description || 'No description provided' }}
                                            </p>
                                        </div>
                                        <!-- Portfolio Preview -->
                                        <div>
                                            <p class="text-sm font-medium text-gray-500 mb-2">Portfolio</p>
                                            <div v-if="selectedContractor.portfolio && selectedContractor.portfolio.url" class="border border-gray-200 rounded-md p-2">
                                                <iframe
                                                    :src="selectedContractor.portfolio.url"
                                                    class="w-full"
                                                    style="height: 400px;"
                                                    frameborder="0"
                                                ></iframe>
                                                <a
                                                    :href="selectedContractor.portfolio.url"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="mt-2 inline-flex items-center text-sm text-[#AE7A42] hover:text-[#8c5e30]"
                                                >
                                                    Download Portfolio
                                                </a>
                                            </div>
                                            <div v-else class="text-gray-400 text-sm">Belum ada portfolio</div>
                                            <!-- Edit Portfolio Button -->
                                            <div class="mt-4">
                                                <button
                                                    class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-sm font-medium text-gray-700"
                                                    @click="startEditPortfolio"
                                                    v-if="!editingPortfolio"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.828a4 4 0 00-5.656-5.656L7 7" />
                                                    </svg>
                                                    Edit Portfolio
                                                </button>
                                                <form v-if="editingPortfolio" @submit.prevent="savePortfolio" class="flex flex-col gap-2">
                                                    <label class="flex items-center gap-2 cursor-pointer">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.828a4 4 0 00-5.656-5.656L7 7" />
                                                        </svg>
                                                        <span>Lampirkan file</span>
                                                        <input type="file" class="hidden" @change="handlePortfolioFile" accept=".pdf,.jpg,.jpeg,.png,.gif" />
                                                    </label>
                                                    <span v-if="portfolioFile" class="text-xs text-gray-600">{{ portfolioFile.name }}</span>
                                                    <div class="flex gap-2 mt-2">
                                                        <button
                                                            type="submit"
                                                            class="px-4 py-2 bg-[#AE7A42] text-white rounded-md text-sm"
                                                            :disabled="uploadingPortfolio"
                                                        >
                                                            <span v-if="uploadingPortfolio">Menyimpan...</span>
                                                            <span v-else>Edit Portfolio</span>
                                                        </button>
                                                        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm" @click="editingPortfolio = false">Batal</button>
                                                    </div>
                                                    <span v-if="uploadError" class="text-xs text-red-500">{{ uploadError }}</span>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button
                                type="button"
                                class="mt-3 mb-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#AE7A42]-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                @click="closeModal"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
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

/* Improved responsive styles for contractor cards */
.contractor-card {
    display: flex;
    flex-direction: column;
}

@media (min-width: 1024px) {
    .contractor-card {
        flex-direction: row;
    }
}

/* Ensure text breaks properly */
.break-words {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
}

/* PDF viewer styling */
.pdf-viewer {
    height: 100%;
    width: 100%;
    border: none;
}

@media (max-width: 640px) {
    .pdf-viewer {
        height: 150px;
    }
}

/* Modal transition styles */
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-active .modal-container,
.modal-leave-active .modal-container {
    transition: transform 0.3s ease;
}

.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
    transform: scale(0.95);
}
</style>