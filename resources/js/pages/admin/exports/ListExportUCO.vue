<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { ShoppingCart, Download } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';

interface Batch {
    id: string;
    code: string;
    poo_name: string;
    collection_date: string;
    volume: number;
    nilai: number;
    status: string;
}

interface FinalExport {
    id: string;
    code: string;
    poo_name: string;
    volume: number;
    exported_at: string;
    iscc_document_path: string;
}

const props = defineProps<{
    readyBatches: Batch[];
    history: FinalExport[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Penjualan / Export', href: '/exports' },
];

const formatDate = (d: string) =>
    new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

const formatRupiah = (n: number) =>
    new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n);

const goExport = (batchId: string) => {
    router.visit(`/exports/${batchId}/confirmation`);
};

const downloadISCC = (exportId: string) => {
    window.open(`/exports/${exportId}/download`, '_blank');
};
</script>

<template>

    <Head title="Penjualan / Export" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 items-center">
            <div class="w-full max-w-3xl flex flex-col gap-6">

                <!-- Header -->
                <div>
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center text-[#007C95]">
                            <ShoppingCart class="h-5 w-5" />
                        </div>
                        <h1 class="text-[18px] font-bold text-gray-900">Penjualan / Final Export</h1>
                    </div>
                    <p class="text-[14px] text-gray-500 mt-0.5">
                        Jual UCO ke refinery dan generate dokumen ISCC self declaration
                    </p>
                </div>

                <!-- Batch Siap Export -->
                <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-5 py-4">
                        <h2 class="text-sm font-semibold text-gray-700">
                            Batch Siap Export
                            (
                            {{ props.readyBatches.length }}
                            )
                        </h2>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto px-5 pb-4">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 bg-gray-50/60">
                                    <th
                                        class="px-4 py-2 text-left text-xs font-semibold tracking-wide whitespace-nowrap">
                                        Kode Batch
                                    </th>

                                    <th
                                        class="px-4 py-2 text-left text-xs font-semibold tracking-wide whitespace-nowrap">
                                        POO
                                    </th>

                                    <th
                                        class="px-4 py-2 text-left text-xs font-semibold tracking-wide whitespace-nowrap">
                                        Tanggal Ambil
                                    </th>

                                    <th
                                        class="px-4 py-2 text-left text-xs font-semibold tracking-wide whitespace-nowrap">
                                        Volume
                                    </th>

                                    <th
                                        class="px-4 py-2 text-left text-xs font-semibold tracking-wide whitespace-nowrap">
                                        Nilai
                                    </th>

                                    <th
                                        class="px-4 py-2 text-left text-xs font-semibold tracking-wide whitespace-nowrap">
                                        Status
                                    </th>

                                    <th
                                        class="px-4 py-2 text-left text-xs font-semibold tracking-wide whitespace-nowrap">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-for="batch in props.readyBatches" :key="batch.id"
                                    class="hover:bg-gray-50/50 transition">
                                    <td class="px-5 py-3.5 ">{{ batch.code }}</td>
                                    <td class="px-5 py-3.5 ">{{ batch.poo_name }}</td>
                                    <td class="px-5 py-3.5">{{ formatDate(batch.collection_date) }}</td>
                                    <td class="px-5 py-3.5 ">{{ batch.volume }} L</td>
                                    <td class="px-5 py-3.5 ">{{ formatRupiah(batch.nilai) }}</td>
                                    <td class="px-5 py-3.5">
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700">
                                            {{ batch.status }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <button @click="goExport(batch.id)"
                                            class="rounded-lg border border-primary px-4 py-1.5 text-xs font-semibold text-primary transition">
                                            Export
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="props.readyBatches.length === 0">
                                    <td colspan="7" class="px-5 py-10 text-center text-sm text-gray-400">
                                        Tidak ada batch siap export
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Riwayat Final Export -->
                <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h2 class="text-sm font-semibold text-gray-700">
                            Riwayat Final Export
                            (
                            {{ props.history.length }}
                            )
                        </h2>
                    </div>

                    <div class="divide-y divide-gray-100">
                        <div v-for="item in props.history" :key="item.id"
                            class="flex items-center justify-between px-5 py-4 hover:bg-gray-50/50 transition">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-teal-600">{{ item.code }}</span>
                                    <span
                                        class="inline-flex items-center rounded-full bg-purple-100 px-2 py-0.5 text-[10px] font-bold text-purple-600 uppercase tracking-wider">
                                        Final Export
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500">{{ item.poo_name }} • {{ item.volume }} L</p>
                                <p class="text-xs text-gray-400">Ekspor: {{ formatDate(item.exported_at) }}</p>
                            </div>
                            <button @click="downloadISCC(item.id)"
                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-600 text-white hover:bg-teal-700 transition shadow-sm flex-shrink-0">
                                <Download class="h-4 w-4" />
                            </button>
                        </div>

                        <div v-if="props.history.length === 0" class="px-5 py-10 text-center text-sm text-gray-400">
                            Belum ada riwayat export
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>