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

const designers = ref([]);
const showEditModal = ref(false);
const currentDesign = ref<any>(null);
const isLoading = ref(false);

onMounted(async () => {
    await fetchDesigns();
});

const fetchDesigns = async () => {
    const response = await axios.get('/api/designs');
    designers.value = response.data.map(d => ({
        ...d,
        photo_url: d.photo ? `data:image/jpeg;base64,${d.photo}` : '/images/design.jpg'
    }));
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
        <div class="flex justify-between items-center px-8 my-6">
            <h2 class="text-2xl font-archivo font-medium text-[#AE7A42]">Designs</h2>
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
        <div class="max-w-6xl mx-auto px-8 pb-12">
            <div v-if="designers.length === 0" class="text-center py-8 text-[#AE7A42]">
                <p>No designs available</p>
            </div>
            
            <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <div v-for="designer in designers" :key="designer.id" 
                     class="bg-white rounded-xl shadow-md overflow-hidden transition-transform hover:-translate-y-6">
                    <img :src="designer.photo_url" :alt="designer.name"
                         class="w-full h-64 object-cover"
                         @error="handleImageError">
                    <div class="p-3 text-center">
                        <h3 class="text-lg font-medium">{{ designer.name }}</h3>
                        <p class="text-gray-500 text-sm">{{ designer.country }}</p>
                        <p class="text-gray-600">{{ designer.specialty }}</p>
                    </div>
                    <div class="flex justify-between p-3 border-t">
                        <button @click="openEditModal(designer)"
                                class="flex items-center gap-1 px-3 py-1 bg-[#AE7A42] text-white rounded text-sm hover:bg-[#8c5e30]">
                            <Pencil :size="18" />
                            Edit
                        </button>
                        <button @click="handleDelete(designer.id)"
                                class="flex items-center gap-1 px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600">
                            <Trash2 :size="18" />
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>