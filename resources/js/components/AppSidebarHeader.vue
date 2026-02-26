<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Bell, ChevronDown } from 'lucide-vue-next';
import { computed } from 'vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { useInitials } from '@/composables/useInitials';
import type { BreadcrumbItem } from '@/types';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const page = usePage();
const user = page.props.auth.user;
const { getInitials } = useInitials();

const showAvatar = computed(
    () => user.avatar && user.avatar !== '',
);

const userSubtitle = computed(() => {
    const u = user as Record<string, unknown>;
    return (u.company as string) || user.email;
});
</script>

<template>
    <header
        class="sticky top-0 z-10 bg-white flex h-16 shrink-0 items-center justify-between gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>

        <div class="flex items-center gap-4">
            <!-- Notification Bell -->
            <button type="button" class="relative rounded-full p-2 hover:bg-gray-100">
                <Bell class="size-5 text-gray-600" />
                <span class="absolute top-1.5 right-1.5 size-2.5 rounded-full bg-red-500 ring-2 ring-white" />
            </button>

            <!-- User Info Dropdown -->
            <DropdownMenu>
                <DropdownMenuTrigger class="flex items-center gap-3 rounded-lg px-2 py-1.5 hover:bg-gray-100 focus:outline-none">
                    <Avatar class="h-9 w-9 overflow-hidden rounded-full">
                        <AvatarImage v-if="showAvatar" :src="user.avatar!" :alt="user.name" />
                        <AvatarFallback class="rounded-full bg-teal-600 text-white text-sm">
                            {{ getInitials(user.name) }}
                        </AvatarFallback>
                    </Avatar>
                    <div class="hidden text-left text-sm leading-tight md:block">
                        <div class="truncate font-semibold text-gray-900">{{ user.name }}</div>
                        <div class="truncate text-xs text-gray-500">{{ userSubtitle }}</div>
                    </div>
                    <ChevronDown class="hidden size-4 text-gray-400 md:block" />
                </DropdownMenuTrigger>
                <DropdownMenuContent
                    class="w-56 rounded-lg"
                    align="end"
                    :side-offset="8"
                >
                    <UserMenuContent :user="user" />
                </DropdownMenuContent>
            </DropdownMenu>
        </div>
    </header>
</template>
