<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';

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
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-4xl"> <!-- Increased max width -->
                <div class="bg-white shadow-md sm:rounded-lg sm:mr-4 border-black-400 border-2">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="font-archivo font-semibold text-[20px] text-black">Update User Role</h2>
                        <div class="mt-2 max-w-xl text-[15px] text-gray-500">
                            <p>Tambahkan designer / kontraktor baru dengan update role user</p>
                        </div>
                        <form @submit.prevent="submit" class="mt-5">
                            <div class="flex flex-col sm:flex-row gap-4 items-end"> <!-- Changed layout -->
                                <div class="w-full sm:w-96"> <!-- Wider email field -->
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                                        placeholder="email@example.com"
                                        required
                                    />
                                </div>
                                <div class="w-full sm:w-64"> <!-- Role select -->
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                    <select
                                        v-model="form.role"
                                        id="role"
                                        name="role"
                                        class="block w-full rounded-md border-0 py-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                                    >
                                        <option value="designer">Designer</option>
                                        <option value="kontraktor">Kontraktor</option>
                                    </select>
                                </div>
                                <button
                                    type="submit"
                                    class="w-full sm:w-48 h-11 flex items-center justify-center rounded-md bg-[#AE7A42] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#9c6d3a] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#AE7A42] transition-colors"
                                    :disabled="form.processing"
                                >
                                    <span v-if="form.processing">Processing...</span>
                                    <span v-else>Update Role</span>
                                </button>
                            </div>
                        </form>
                        <div v-if="form.recentlySuccessful" class="mt-3 text-sm text-green-600">
                            Role berhasil diupdate!
                        </div>
                        <div v-if="form.errors.email" class="mt-3 text-sm text-red-600">
                            {{ form.errors.email }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
