<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Ban, UserPlus, Mails, HousePlus, Blocks, Send, LayoutDashboard } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'E-Mail',
        href: '/admin/email',
        icon: Mails,
    },
    {
        title: 'New Design',
        href: '/admin/new',
        icon: HousePlus,
    },
    {
        title: 'Add Role Users',
        href: '/admin/add',
        icon: UserPlus,
    },
    {
        title: 'Ban',
        href: '/admin/ban',
        icon: Ban,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Build',
        href: '/build',
        icon: Blocks,
    },
    {
        title: 'Design',
        href: '/design',
        icon: LayoutDashboard,
    },
    // {
    //     title: 'Request',
    //     href: '/request',
    //     icon: Send,
    // },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('admin')" class="flex items-center gap-x-2">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
