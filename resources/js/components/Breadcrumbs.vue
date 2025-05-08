<script setup lang="ts">
import { Breadcrumb, BreadcrumbItem, BreadcrumbLink, BreadcrumbList, BreadcrumbPage, BreadcrumbSeparator } from '@/components/ui/breadcrumb';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

interface BreadcrumbItem {
    title: string;
    href?: string;
}

defineProps<{
    breadcrumbs: BreadcrumbItem[];
}>();

</script>

<template>
    <div class="breadcrumb-container">
        <Breadcrumb>
            <BreadcrumbList class="breadcrumb-list">
                
                    <template v-for="(item, index) in breadcrumbs" :key="index">
                        <BreadcrumbItem>
                            <template v-if="index === breadcrumbs.length - 1">
                                <BreadcrumbPage class="breadcrumb-current font-archivo text-[20px] text-[#AE7A42]">
                                    <Link href="/admin"><b>Dr.Home Admin</b></Link></BreadcrumbPage>
                            </template>
                            <template v-else>
                                <BreadcrumbLink as-child>
                                    <Link :href="item.href ?? '#'" class="breadcrumb-link">{{ item.title }}</Link>
                                </BreadcrumbLink>
                            </template>
                        </BreadcrumbItem>
                        <BreadcrumbSeparator v-if="index !== breadcrumbs.length - 1" class="breadcrumb-separator" />
                    </template>
                
            </BreadcrumbList>
        </Breadcrumb>
    </div>
</template>