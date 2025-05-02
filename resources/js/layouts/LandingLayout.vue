<script setup lang="ts">
import LandingHeader from '@/components/landing/LandingHeader.vue';
import LandingFooter from '@/components/landing/LandingFooter.vue';
import { Head } from '@inertiajs/vue3';
import AppHeaderLayout from './app/AppHeaderLayout.vue';
import AppHeader from '@/components/AppHeader.vue';

defineProps<{
    title?: string;
}>();
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <Head :title="title" />
        <AppHeader />

        <main>
            <slot />
        </main>

        <LandingFooter />
    </div>
</template>
