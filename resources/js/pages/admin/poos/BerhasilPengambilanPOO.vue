<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { CheckCircle2, Download } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';

interface Batch {
    id: number;
    code: string;
    poo_name: string;
    volume: number;
    collection_date: string;
    status: string;
    qr_code_url: string;
}

const props = defineProps<{
    batch: Batch;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Pengambilan dari POO', href: '/poos' },
    { title: 'Berhasil', href: '#' },
];

const formattedDate = new Date(props.batch.collection_date).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
});

const downloadQR = () => {
    const link = document.createElement('a');
    link.href = props.batch.qr_code_url;
    link.download = `QR-${props.batch.code}.png`;
    link.click();
};

const pengambilanBaru = () => {
    router.visit('/poos');
};
</script>

<template>

    <Head title="Pengambilan Berhasil" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 items-center">
            <div class="w-full max-w-xl flex flex-col gap-5">

                <!-- Card -->
                <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">

                    <!-- Success Banner -->
                    <div class="flex flex-col items-center px-6 pt-8 pb-6 text-center">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-green-100 mb-4">
                            <CheckCircle2 class="h-8 w-8 text-green-500" />
                        </div>
                        <h1 class="text-[17px] font-bold text-gray-900">Pengambilan Berhasil Dicatat!</h1>
                        <p class="text-sm text-gray-500 mt-1 max-w-xs">
                            Batch UCO dari <span class="font-semibold text-gray-700">{{ props.batch.poo_name }}</span>
                            sebesar <span class="font-semibold text-gray-700">{{ props.batch.volume }} Liter</span>
                            telah berhasil dicatat dan masuk ke stok Anda.
                        </p>
                    </div>

                    <!-- QR Section -->
                    <div class="flex flex-col items-center px-6 pb-6">
                        <div class="w-fit rounded-2xl bg-primary-surface flex flex-col items-center py-5 px-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">QR Batch</p>
                            <div class="rounded-xl border-3 border-primary p-3 bg-white shadow-sm">
                                <img :src="props.batch.qr_code_url" :alt="`QR ${props.batch.code}`"
                                    class="h-28 w-28 object-contain" />
                            </div>
                            <p class="mt-3 text-sm font-bold text-teal-600 tracking-wide">{{ props.batch.code }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Scan untuk verifikasi kepemilikan</p>
                        </div>
                    </div>

                    <!-- Batch Details -->
                    <div class="px-6 py-5 ">
                        <div class="grid grid-cols-2 gap-y-3 text-sm bg-gray-50 p-4 rounded-core">
                            <span class="text-gray-500">Kode Batch</span>
                            <span class="text-right font-semibold text-teal-600">{{ props.batch.code }}</span>

                            <span class="text-gray-500">POO</span>
                            <span class="text-right font-semibold text-gray-900">{{ props.batch.poo_name }}</span>

                            <span class="text-gray-500">Volume</span>
                            <span class="text-right font-semibold text-gray-900">{{ props.batch.volume }} Liter</span>

                            <span class="text-gray-500">Tanggal</span>
                            <span class="text-right font-semibold text-gray-900">{{ formattedDate }}</span>

                            <span class="text-gray-500">Status</span>
                            <span class="text-right">
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold text-[#00A63E]">
                                    Aktif
                                </span>
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="grid grid-cols-2 gap-3 p-5">
                        <Button @click="pengambilanBaru"
                            class="w-full bg-primary hover:bg-primary-hover text-white font-medium rounded">
                            Pengambilan Baru
                        </Button>
                        <Button variant="outline" @click="downloadQR"
                            class="w-full text-gray-700 font-medium rounded border-gray-200 hover:border-teal-400 hover:text-teal-600 transition">
                            <Download class="mr-1.5 h-4 w-4" />
                            Download QR
                        </Button>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>