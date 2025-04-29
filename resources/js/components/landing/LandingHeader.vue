<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <header class="fixed top-0 right-0 left-0 z-50 bg-white/80 backdrop-blur-sm">
        <nav class="container mx-auto flex items-center justify-between px-4 py-4">
            <div class="flex items-center">
                <Link href="/" class="text-2xl font-bold text-[#B07D48]">Dr. Home</Link>
            </div>

            <div class="flex items-center space-x-8">
                <Link href="/" class="text-gray-700 hover:text-[#B07D48]">Home</Link>
                <Link href="/build" class="text-gray-700 hover:text-[#B07D48]">Build</Link>
                <Link href="/design" class="text-gray-700 hover:text-[#B07D48]">Design</Link>
                <Link href="/login" class="rounded-full bg-[#B07D48] px-6 py-2 text-white transition-colors hover:bg-[#95683C]"> Login </Link>
            </div>
        </nav>
    </header>
    <!-- Spacer to prevent content from going under fixed header -->
    <div class="h-16"></div>
</template>
