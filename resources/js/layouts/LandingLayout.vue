<script setup lang="ts">
import LandingFooter from '@/components/landing/LandingFooter.vue';
import LandingHeader from '@/components/landing/LandingHeader.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    title?: string;
}>();
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <Head :title="title" />
        <LandingHeader />

        <main>
            <slot />
        </main>

        <LandingFooter />
    </div>
</template>
