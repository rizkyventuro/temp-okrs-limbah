<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    LayoutGrid,
    ShoppingCart,
    ArrowLeftRight,
    TrendingUp,
    History,
    MapPin,
    User,
} from 'lucide-vue-next';
import NavMain from '@/components/NavMain.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Pengambilan dari POO',
        href: '/admin/pengambilan',
        icon: ShoppingCart,
    },
    {
        title: 'Transfer UCO',
        href: '/admin/transfer',
        icon: ArrowLeftRight,
    },
    {
        title: 'Penjualan / Export',
        href: '/admin/penjualan',
        icon: TrendingUp,
    },
    {
        title: 'Riwayat Transaksi',
        href: '/admin/riwayat',
        icon: History,
    },
    {
        title: 'Manajemen POO',
        href: '/admin/manajemen-poo',
        icon: MapPin,
    },
    {
        title: 'Profil Akun',
        href: '/admin/profil',
        icon: User,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter />
    </Sidebar>
    <slot />
</template>
