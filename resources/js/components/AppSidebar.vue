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

import IconDashboard from '@/components/icons/menu/IconDashboard.vue';
import IconPengambilan from '@/components/icons/menu/iconPengambilan.vue';
import IconTransfer from '@/components/icons/menu/iconTransfer.vue';
import IconPenjualan from '@/components/icons/menu/iconPenjualan.vue';
import IconRiwayat from '@/components/icons/menu/iconRiwayatTransaksi.vue';
import IconManajemen from '@/components/icons/menu/iconManajemen.vue';
import IconProfil from '@/components/icons/menu/iconProfile.vue';



const mainNavItems: NavItem[] = [
    { title: 'Dashboard', href: dashboard(), icon: IconDashboard },
    { title: 'Pengambilan dari POO', href: '/poos', icon: IconPengambilan },
    { title: 'Transfer UCO', href: '/transfer', icon: IconTransfer },
    { title: 'Penjualan / Export', href: '/penjualan', icon: IconPenjualan },
    { title: 'Riwayat Transaksi', href: '/riwayat', icon: IconRiwayat },
    { title: 'Manajemen POO', href: '/manajemen-poo', icon: IconManajemen },
    { title: 'Profil Akun', href: '/profil', icon: IconProfil },
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
