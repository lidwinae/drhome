<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Ban, CircleCheck, Trash2 } from 'lucide-vue-next';
import { ref, onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Ban',
        href: '/admin/ban',
    },
];

interface User {
    id: number;
    name: string;
    email: string;
    role: string;
    status: string;
    created_at: string;
}

const users = ref<User[]>([]);
const form = useForm({});
const isLoading = ref(false);

const fetchUsers = async () => {
    try {
        isLoading.value = true;
        const response = await fetch('/api/admin/users', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'include'
        });
        users.value = await response.json();
    } catch (error) {
        console.error('Failed to fetch users:', error);
    } finally {
        isLoading.value = false;
    }
};

const banUser = async (userId: number) => {
    if (confirm('Are you sure you want to ban this user?')) {
        await form.patch(route('users.ban', userId));
        await fetchUsers();
    }
};

const unbanUser = async (userId: number) => {
    if (confirm('Are you sure you want to unban this user?')) {
        await form.patch(route('users.unban', userId));
        await fetchUsers();
    }
};

const deleteUser = async (userId: number) => {
    if (confirm('Are you sure you want to delete this user?')) {
        await form.delete(route('users.destroy', userId));
        await fetchUsers();
    }
};

onMounted(() => {
    fetchUsers();
});
</script>

<template>
    <Head title="Ban" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-12 mt-10 space-y-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-archivo font-semibold">User Management</h2>
            </div>

            <!-- Loading State -->
            <div v-if="isLoading" class="flex flex-col items-center justify-center py-8 text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-[#AE7A42] mb-4"></div>
                <p class="text-black mt-2">Loading user list...</p>
            </div>

            <!-- User Cards -->
            <div v-else class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-2 mb-16">
                <div v-for="user in users" :key="user.id" class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <img
                                    v-if="user.avatar || user.avatar_url"
                                    :src="user.avatar_url || ('/storage/' + user.avatar)"
                                    alt="Avatar"
                                    class="w-9 h-9 rounded-full object-cover border border-gray-200"
                                />
                                <span v-else class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 text-lg font-bold">
                                    {{ user.name.charAt(0) }}
                                </span>
                                <h3 class="text-lg font-medium text-gray-900">{{ user.name }}</h3>
                            </div>
                            <span 
                                :class="{
                                    'bg-red-100 text-red-800': user.status === 'banned',
                                    'bg-green-100 text-green-800': user.status === 'active'
                                }" 
                                class="px-2 py-1 text-xs font-semibold rounded-full"
                            >
                                {{ user.status }}
                            </span>
                        </div>
                        
                        <p class="text-sm text-gray-500">{{ user.email }}</p>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex space-x-2 items-center">
                                <span 
                                    :class="{
                                        'bg-blue-100 text-blue-800': user.role === 'client',
                                        'bg-purple-100 text-purple-800': user.role === 'designer',
                                        'bg-orange-100 text-orange-800': user.role === 'contractor',
                                        'bg-gray-100 text-gray-800': !['client', 'designer', 'contractor'].includes(user.role)
                                    }"
                                    class="px-2 py-1 text-xs font-semibold rounded-full"
                                >
                                    {{ user.role }}
                                </span>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button
                                    v-if="user.status === 'active'"
                                    @click="banUser(user.id)"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded"
                                    :disabled="user.role === 'admin'"
                                    title="Ban User"
                                >
                                    <Ban :size="18" />
                                </button>
                                <button
                                    v-else
                                    @click="unbanUser(user.id)"
                                    class="p-2 text-green-600 hover:bg-green-50 rounded"
                                    :disabled="user.role === 'admin'"
                                    title="Unban User"
                                >
                                    <CircleCheck :size="18" />
                                </button>
                                <button
                                    @click="deleteUser(user.id)"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded"
                                    :disabled="user.role === 'admin'"
                                    title="Delete User"
                                >
                                    <Trash2 :size="18" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>