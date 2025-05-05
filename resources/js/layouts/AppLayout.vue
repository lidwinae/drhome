<script setup lang="ts">
import AppLayout from '@/layouts/app/AppHeaderLayout.vue';
import LandingFooter from '@/components/landing/LandingFooter.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <AppLayout :breadcrumbs="breadcrumbs" class="flex-grow">
      <slot />
    </AppLayout>
    <LandingFooter />
  </div>
</template>
