<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Pencil, Trash2, HousePlus } from 'lucide-vue-next';
import axios from 'axios';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'New', href: '/admin/new' },
];

const designs = ref([]);
const showEditModal = ref(false);
const currentDesign = ref<any>(null);
const isLoading = ref(false);
const isFetching = ref(true); // Add this line for loading state

onMounted(async () => {
    await fetchDesigns();
});

const fetchDesigns = async () => {
    isFetching.value = true;
    try {
        const response = await axios.get('/api/designs');
        designs.value = response.data.map(d => ({
            ...d,
            photo_url: d.photo ? `data:image/jpeg;base64,${d.photo}` : '/images/design.jpg'
        }));
    } catch (error) {
        console.error('Error fetching designs:', error);
    } finally {
        isFetching.value = false;
    }
};

const openEditModal = (design: any) => {
    currentDesign.value = { 
        ...design,
        photoFile: null,
        photo_preview: design.photo_url
    };
    showEditModal.value = true;
};

const handleDelete = async (id: number) => {
    if (!confirm('Apakah anda yakin untuk menghapus design?')) return;
    await axios.delete(`/api/designs/${id}`, {
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
    }
});
    await fetchDesigns();
};

const handleUpdate = async () => {
    isLoading.value = true;
    
    try {
        const formData = new FormData();
        formData.append('name', currentDesign.value.name);
        formData.append('country', currentDesign.value.country);
        formData.append('specialty', currentDesign.value.specialty);
        
        if (currentDesign.value.photoFile) {
            formData.append('photo', currentDesign.value.photoFile);
        }

        if (!confirm('Apakah anda yakin untuk update design?')) return;
        await axios.post(`/api/designs/${currentDesign.value.id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            params: { _method: 'PATCH' }
        });

        await fetchDesigns();
    } catch (error) {
        console.error('Update error:', error);
    } finally {
        isLoading.value = false;
        showEditModal.value = false;
        await fetchDesigns();
    }
};

const handleFileChange = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.files?.[0]) {
        currentDesign.value.photoFile = input.files[0];
        currentDesign.value.photo_preview = URL.createObjectURL(input.files[0]);
    }
};

const handleImageError = (event: Event) => {
    (event.target as HTMLImageElement).src = '/images/design.jpg';
};
</script>

<template>
    <Head title="Design" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-between items-center px-10 my-6">
            <h2 class="text-2xl font-archivo font-semibold">Designs</h2>
            <a href="/admin/new/design" class="bg-[#AE7A42] text-white px-4 py-2 rounded-lg font-medium flex items-center hover:bg-[#8c5e30] transition">
                <HousePlus :size="18" class="mr-2" />
                Add New Design
            </a>
        </div>

        <!-- Edit Modal -->
        <div v-if="showEditModal" class="fixed inset-0 bg-black/50 flex justify-center items-center z-50 p-4">
            <div class="bg-white rounded-lg w-full max-w-md max-h-[90vh] flex flex-col">
                <div class="p-6 border-b">
                    <h3 class="text-xl font-semibold">Edit Design</h3>
                </div>
                
                <div class="overflow-y-auto p-6 flex-1">
                    <form @submit.prevent="handleUpdate">
                        <div class="mb-4">
                            <label class="block mb-1">Name</label>
                            <input v-model="currentDesign.name" type="text" 
                                class="w-full p-2 border rounded" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block mb-1">Country</label>
                            <input v-model="currentDesign.country" type="text" 
                                class="w-full p-2 border rounded" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block mb-1">Specialty</label>
                            <input v-model="currentDesign.specialty" type="text" 
                                class="w-full p-2 border rounded" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block mb-1">Photo</label>
                            <input type="file" @change="handleFileChange" 
                                class="w-full p-2 border rounded">
                            <img v-if="currentDesign.photo_preview" 
                                :src="currentDesign.photo_preview" 
                                class="mt-2 max-w-full max-h-48 object-contain">
                        </div>
                    </form>
                </div>
                
                <div class="p-4 border-t flex justify-end gap-4">
                    <button type="button" @click="showEditModal = false" 
                            class="px-4 py-2 bg-gray-200 rounded">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-[#AE7A42] text-white rounded flex items-center justify-center gap-2 min-w-32"
                            :disabled="isLoading"
                            @click="handleUpdate">
                        <span v-if="isLoading" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                        <span v-else>Update Design</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Design List -->
        <div class="max-w-6xl mx-auto px-10 pb-12">
            <!-- Loading State -->
            <div v-if="isFetching" class="flex flex-col items-center justify-center py-8 text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-[#AE7A42] mb-4"></div>
                <p class="text-black mt-2">Loading designs...</p>
            </div>
            
            <!-- Empty State -->
            <div v-else-if="designs.length === 0 && !isFetching" class="text-center py-8 text-[#AE7A42]">
                <p>No designs available</p>
            </div>
            
            <!-- Design Grid -->
            <div v-else class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <div v-for="design in designs" :key="design.id" 
                     class="bg-white rounded-xl shadow-md overflow-hidden transition-transform hover:-translate-y-4">
                    <img :src="design.photo_url" :alt="design.name"
                         class="w-full h-64 object-cover"
                         @error="handleImageError">
                    <div class="p-3 text-center space-y-2">
                    <!-- Design Name -->
                    <h3 class="m-0 mb-3 text-[22px] font-medium text-gray-800 transition-colors duration-200 hover:text-[#AE7A42]">
                        {{ design.name }}
                    </h3>

                    <!-- Country -->
                    <div class="flex justify-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#FAE5CC] text-[#714C25]">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ design.country }}
                        </span>
                    </div>

                    <!-- Specialty -->
                    <div class="flex justify-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        {{ design.specialty }}
                        </span>
                    </div>
                    </div>
                    <div class="flex justify-between p-3 border-t gap-2">
                    <button @click="openEditModal(design)"
                            class="w-[48%] flex flex-col items-center justify-center gap-1 px-2 py-2 bg-[#AE7A42] text-white rounded text-sm hover:bg-[#8c5e30] transition-colors">
                        <Pencil :size="18" />
                        <span class="text-[14px]">Edit</span>
                    </button>
                    <button @click="handleDelete(design.id)"
                            class="w-[48%] flex flex-col items-center justify-center gap-1 px-2 py-2 bg-red-500 text-white rounded text-sm hover:bg-red-600 transition-colors">
                        <Trash2 :size="18" />
                        <span class="text-[14px]">Delete</span>
                    </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
