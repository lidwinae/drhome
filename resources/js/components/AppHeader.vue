<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { getInitials } from '@/composables/useInitials';
import type { BreadcrumbItem, NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, Menu, Search } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItem[];
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();
const auth = computed(() => page.props.auth);

const isCurrentRoute = computed(() => (url: string) => page.url === url);

const activeItemStyles = computed(
    () => (url: string) => (isCurrentRoute.value(url) ? 'text-neutral-900 dark:bg-neutral-800 dark:text-neutral-100' : ''),
);
const isLoggedIn = computed(() => !!auth.value?.user);

const mainNavItems: NavItem[] = [
    {
        title: 'Home',
        href: '/',
        icon: Menu,
    },
    {
        title: 'Build',
        href: '/build',
        icon: LayoutGrid,
    },
    {
        title: 'Design',
        href: '/design',
        icon: BookOpen,
    },
];

const rightNavItems: NavItem[] = [

];
</script>

<template>
    <div>
        <div class="border-b border-sidebar-border/80">
            <div class="mx-auto flex h-16 items-center px-4 md:max-w-7xl">
                <!-- Mobile Menu -->
                <div class="lg:hidden">
                    <Sheet>
                        <SheetTrigger :as-child="true">
                            <Button variant="ghost" size="icon" class="mr-2 h-9 w-9">
                                <Menu class="h-5 w-5" />
                            </Button>
                        </SheetTrigger>
                        <SheetContent side="left" class="w-[300px] p-6">
                            <SheetTitle class="sr-only">Navigation Menu</SheetTitle>
                            <SheetHeader class="flex justify-start text-left">
                                <Link :href="route('home')">
                                <AppLogoIcon class="size-6 fill-current text-black dark:text-white mb-2 ml-16" />
                                <span class="ml-8 mb-0.5 truncate font-bold text-xl leading-none text-[#AE7A42] hover:text-[#c08d5a] transition-colors">
                                    Dr. Home
                                </span>
                                </Link>
                            </SheetHeader>
                            <div class="flex h-full flex-1 flex-col justify-between space-y-4 py-6">
                                <nav class="-mx-3 space-y-1">
                                    <Link
                                        v-for="item in mainNavItems"
                                        :key="item.title"
                                        :href="item.href"
                                        class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent"
                                        :class="activeItemStyles(item.href)"
                                    >
                                        <component v-if="item.icon" :is="item.icon" class="h-5 w-5" />
                                        {{ item.title }}
                                    </Link>
                                </nav>
                                <div class="flex flex-col space-y-4">
                                    <Link
                                        v-for="item in rightNavItems"
                                        :key="item.title"
                                        :href="item.href"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="flex items-center space-x-2 text-sm font-medium"
                                        :class="activeItemStyles(item.href)"
                                    >
                                        <component v-if="item.icon" :is="item.icon" class="h-5 w-5" />
                                        <span>{{ item.title }}</span>
                                    </Link>
                                </div>
                            </div>
                        </SheetContent>
                    </Sheet>
                </div>

                <Link :href="route('home')" class="flex items-center gap-x-2">
                    <AppLogo />
                </Link>

                <!-- Desktop Menu -->
                <div class="hidden h-full lg:flex lg:flex-1">
                    
                </div>

                <div class="ml-auto h-full flex items-center space-x-2">
                    <NavigationMenu class="ml-10 flex hidden h-full items-stretch lg:flex">
                        <NavigationMenuList class="flex h-full items-stretch space-x-2">
                        <NavigationMenuItem 
                            v-for="(item, index) in mainNavItems" 
                            :key="index" 
                            class="relative flex h-full items-center"
                        >
                            <Link :href="item.href">
                            <NavigationMenuLink
                                :class="[navigationMenuTriggerStyle(), activeItemStyles(item.href), 'h-9 cursor-pointer px-3 font-archivo font-medium text-lg flex items-center text-[#AE7A42] hover:text-[#c08d5a]']"
                            >
                                <component v-if="item.icon" :is="item.icon" class="mr-2 h-4 w-15" />
                                {{ item.title }}
                            </NavigationMenuLink>
                            </Link>
                            <div
                            v-if="isCurrentRoute(item.href)"
                            class="absolute bottom-0 left-0 h-0.5 w-full translate-y-px bg-[#AE7A42]"
                            ></div>
                        </NavigationMenuItem>
                        </NavigationMenuList>
                    </NavigationMenu>   
  <template v-if="isLoggedIn">
    <DropdownMenu>
      <DropdownMenuTrigger :as-child="true">
        <Button
          variant="ghost"
          size="icon"
          class="relative size-10 ml-4 w-auto rounded-full p-1 focus-within:ring-2 focus-within:ring-primary"
        >
          <Avatar class="size-8 overflow-hidden rounded-full">
            <AvatarImage v-if="auth.user.avatar" :src="auth.user.avatar" :alt="auth.user.name" />
            <AvatarFallback class="rounded-lg bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white">
              {{ getInitials(auth.user?.name) }}
            </AvatarFallback>
          </Avatar>
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="end" class="w-56">
        <UserMenuContent :user="auth.user" />
      </DropdownMenuContent>
    </DropdownMenu>
  </template>
  <template v-else>
    <div class="flex gap-2">
      <Button as-child variant="outline" class="rounded-full bg-[#B07D48] px-8 py-2 text-white transition-colors hover:bg-[#95683C]">
        <Link :href="route('login')">Login</Link>
      </Button>
    </div>
  </template>
                </div>
            </div>
        </div>

        <div v-if="props.breadcrumbs.length > 1" class="flex w-full border-b border-sidebar-border/70">
            <div class="mx-auto flex h-12 w-full items-center justify-start px-4 text-neutral-500 md:max-w-7xl">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </div>
        </div>
    </div>
</template>
