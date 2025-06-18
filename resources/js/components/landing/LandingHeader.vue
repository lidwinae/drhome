<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const user = computed(() => usePage().props.auth.user);
</script>

<template>
    <header class="fixed top-0 right-0 left-0 z-50 bg-white/80 backdrop-blur-sm font-archivo">
        <nav class="container mx-auto flex items-center justify-between px-4 py-4">
            <div class="flex items-center">
                <Link href="/" class="text-2xl font-bold text-[#B07D48]">Dr. Home</Link>
            </div>

            <div class="flex items-center space-x-8">

                <!-- Navigation Links -->
                <Link href="/" class="text-gray-700 hover:text-[#B07D48]">Home</Link>
                <Link href="/build" class="text-gray-700 hover:text-[#B07D48]">Build</Link>
                <Link href="/design" class="text-gray-700 hover:text-[#B07D48]">Design</Link>

                <!-- Conditional rendering based on auth status -->
                <div v-if="user">
                    <Link 
                        href="settings/profile" 
                        class="flex items-center space-x-2 rounded-full bg-[#B07D48] px-4 py-2 text-white transition-colors hover:bg-[#95683C]"
                    >
                        <span>{{ user.name.split(' ')[0] }}</span>
                    </Link>
                </div>

                <!-- If user is not authenticated, show Login link -->
                <div v-else>
                    <Link 
                        href="/login" 
                        class="rounded-full bg-[#B07D48] px-6 py-2 text-white transition-colors hover:bg-[#95683C]"
                    >
                        Login
                    </Link>
                </div>
            </div>
        </nav>
    </header>
    
    <!-- Spacer to prevent content from going under fixed header -->
    <div class="h-16"></div>
</template>