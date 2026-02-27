<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { ArrowRightLeft, Send, QrCode, ChevronRight } from 'lucide-vue-next';

interface Batch {
    id: number;
    code: string;
    poo_name: string;
    volume: number;
    status: 'Aktif' | 'Transfer' | 'Selesai';
}

const props = defineProps<{
    batches: Batch[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Transfer UCO',
        href: '/transfers',
    },
];

const navigateToKirim = () => {
    router.visit('/transfers/create');
};

const navigateToTerima = () => {
    router.visit('/transfers/claim');
};
</script>

<template>

    <Head title="Transfer UCO" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 items-center">
            <div class="w-full max-w-3xl flex flex-col gap-6">

                <!-- Header -->
                <div>
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center text-[#007C95]">
                            <ArrowRightLeft class="h-5 w-5" />
                        </div>
                        <h1 class="text-[18px] font-bold text-gray-900">Transfer UCO</h1>
                    </div>
                    <p class="text-[14px] text-gray-500 mt-0.5">
                        Pindahkan kepemilikan minyak jelantah antar pengepul
                    </p>
                </div>

                <!-- Action Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kirim UCO -->
                    <button @click="navigateToKirim"
                        class="group relative flex flex-col gap-3 rounded-2xl border-2 border-primary bg-white p-6 text-left shadow-sm transition hover:bg-primary-surface hover:shadow-md cursor-pointer">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-50 text-primary group-hover:bg-teal-100 transition">
                            <Send class="h-5 w-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Kirim UCO</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Transfer batch ke pengepul lain. QR code akan digenerate untuk penerima.
                            </p>
                        </div>
                    </button>

                    <!-- Terima UCO -->
                    <button @click="navigateToTerima"
                        class="group relative flex flex-col gap-3 rounded-2xl border border-gray-200 bg-white p-6 text-left shadow-sm transition hover:border-teal-300 hover:shadow-md cursor-pointer">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-gray-600 group-hover:bg-teal-50 group-hover:text-teal-600 transition">
                            <QrCode class="h-5 w-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Terima UCO</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Scan QR code dari pengirim untuk menerima kepemilikan batch UCO.
                            </p>
                        </div>
                    </button>
                </div>

                <!-- Batch Siap Transfer -->
                <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h2 class="text-sm font-semibold text-gray-700">
                            Batch Siap Transfer
                            <span
                                class="ml-1.5 inline-flex items-center justify-center rounded-full bg-teal-100 px-2 py-0.5 text-xs font-medium text-teal-700">
                                {{ props.batches?.length ?? 0 }}
                            </span>
                        </h2>
                    </div>

                    <div class="divide-y divide-gray-100">
                        <template v-if="props.batches && props.batches.length > 0">
                            <div v-for="batch in props.batches" :key="batch.id"
                                class="group flex items-center justify-between px-5 py-4 cursor-pointer hover:bg-gray-50 transition"
                                @click="navigateToKirim">
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-sm font-semibold text-teal-600">{{ batch.code }}</span>
                                    <span class="text-xs text-gray-500">{{ batch.poo_name }} • {{ batch.volume }}
                                        L</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span
                                        class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">
                                        ● Aktif
                                    </span>
                                    <ChevronRight class="h-4 w-4 text-gray-300 group-hover:text-teal-500 transition" />
                                </div>
                            </div>
                        </template>

                        <!-- Empty state -->
                        <div v-else class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                                <ArrowRightLeft class="h-4 w-4 text-gray-400" />
                            </div>
                            <p class="text-sm font-medium text-gray-600">Tidak ada batch siap transfer</p>
                            <p class="mt-1 text-xs text-gray-400">Batch akan muncul di sini setelah dicatat</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>