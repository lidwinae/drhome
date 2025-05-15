<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'New Design',
        href: '/admin/new/design',
    },
];

const form = useForm({
    name: '',
    country: '',
    specialty: '',
    photo: null as File | null,
});

const successMessage = ref(false);
const imagePreview = ref<string | null>(null);

const submitForm = () => {
    form.post(route('news'), {
        preserveScroll: true,
        onSuccess: () => {
            successMessage.value = true;
            form.reset();
            imagePreview.value = null;
            setTimeout(() => {
                successMessage.value = false;
            }, 5000);
        },
    });
};

const handleFileChange = (event: Event) => {
    const fileInput = event.target as HTMLInputElement;
    if (!fileInput.files || fileInput.files.length === 0) {
        form.photo = null;
        imagePreview.value = null;
        return;
    }

    const file = fileInput.files[0];
    
    // Validasi tipe file
    const allowedTypes = ['image/jpeg', 'image/png'];
    if (!allowedTypes.includes(file.type)) {
        form.errors.photo = 'Hanya file JPEG dan PNG yang diperbolehkan';
        fileInput.value = '';
        return;
    }

    form.photo = file;
    form.errors.photo = '';

    // Buat preview gambar
    const reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
};
</script>

<template>
    <Head title="New Design" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto p-4 max-w-2xl">
            <!-- Success Message -->
            <div v-if="successMessage" class="mb-6 p-4 bg-green-100 text-green-800 rounded-md border border-green-200">
                Design berhasil dibuat!
            </div>

            <!-- Card Container -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                    <h1 class="text-2xl font-semibold text-black mt-2">Buat Design Baru</h1>
                </div>
                
                <!-- Card Body - Form -->
                <form @submit.prevent="submitForm" class="p-6 space-y-6">
                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Design</label>
                        <input
                            v-model="form.name"
                            type="text"
                            id="name"
                            class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#AE7A42] focus:ring focus:ring-[#AE7A42] focus:ring-opacity-50"
                            placeholder="Masukkan nama design"
                            :class="{ 'border-red-500': form.errors.name }"
                        />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                    </div>

                    <!-- Country Field -->
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Negara</label>
                        <input
                            v-model="form.country"
                            type="text"
                            id="country"
                            class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#AE7A42] focus:ring focus:ring-[#AE7A42] focus:ring-opacity-50"
                            placeholder="Masukkan negara"
                            :class="{ 'border-red-500': form.errors.country }"
                        />
                        <p v-if="form.errors.country" class="mt-1 text-sm text-red-600">{{ form.errors.country }}</p>
                    </div>

                    <!-- Specialty Field -->
                    <div>
                        <label for="specialty" class="block text-sm font-medium text-gray-700 mb-1">Spesialisasi</label>
                        <input
                            v-model="form.specialty"
                            type="text"
                            id="specialty"
                            class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#AE7A42] focus:ring focus:ring-[#AE7A42] focus:ring-opacity-50"
                            placeholder="Masukkan spesialisasi"
                            :class="{ 'border-red-500': form.errors.specialty }"
                        />
                        <p v-if="form.errors.specialty" class="mt-1 text-sm text-red-600">{{ form.errors.specialty }}</p>
                    </div>

                    <!-- Photo Upload -->
                    <div>
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Foto Design</label>
                        <input
                            type="file"
                            id="photo"
                            @change="handleFileChange"
                            accept="image/jpeg, image/png"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#AE7A42] file:text-white hover:file:bg-[#95683C]"
                            :class="{ 'border-red-500': form.errors.photo }"
                        />
                        <p class="mt-1 text-sm text-gray-500">Hanya file JPEG/PNG yang diperbolehkan</p>
                        <p v-if="form.errors.photo" class="mt-1 text-sm text-red-600">{{ form.errors.photo }}</p>
                        
                        <!-- Image Preview -->
                        <div v-if="imagePreview" class="mt-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Preview Gambar:</p>
                            <img :src="imagePreview" alt="Preview" class="max-w-full h-48 object-contain border rounded-md">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button
                            type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#AE7A42] hover:bg-[#95683C] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#AE7A42] disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Menyimpan...</span>
                            <span v-else>Buat Design</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
