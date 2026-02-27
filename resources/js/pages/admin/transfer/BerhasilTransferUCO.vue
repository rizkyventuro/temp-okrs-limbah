<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { CheckCircle2, Download } from 'lucide-vue-next';

interface Transfer {
    id: string;
    transfer_code: string;
    batch_code: string;
    poo_name: string;
    volume: number;
    receiver_name: string;
    receiver_company: string | null;
    status: string;
    qr_code_url: string;
    created_at: string;
}

const props = defineProps<{
    transfer: Transfer;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Transfer UCO', href: '/transfers' },
    { title: 'Berhasil', href: '#' },
];

const formattedDate = new Date(props.transfer.created_at).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
});

const downloadQR = () => {
    const link = document.createElement('a');
    link.href = props.transfer.qr_code_url;
    link.download = `QR-${props.transfer.transfer_code}.png`;
    link.click();
};

const transferBaru = () => {
    router.visit('/transfers');
};
</script>

<template>

    <Head title="Transfer Berhasil" />

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
                        <h1 class="text-[17px] font-bold text-gray-900">Transfer UCO</h1>
                        <p class="text-sm text-gray-500 mt-1 max-w-xs">
                            Batch <span class="font-semibold text-gray-700">{{ props.transfer.batch_code }}</span>
                            sebesar <span class="font-semibold text-gray-700">{{ props.transfer.volume }} Liter</span>
                            telah ditransfer ke <span class="font-semibold text-gray-700">{{
                                props.transfer.receiver_name }}</span>.
                        </p>
                    </div>

                    <!-- QR Section -->
                    <div class="flex flex-col items-center px-6 pb-6">
                        <div class="w-fit rounded-2xl bg-primary-surface flex flex-col items-center py-5 px-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">QR Transfer
                            </p>
                            <div class="rounded-xl border-3 border-primary p-3 bg-white shadow-sm">
                                <img :src="props.transfer.qr_code_url" :alt="`QR ${props.transfer.transfer_code}`"
                                    class="h-28 w-28 object-contain" />
                            </div>
                            <p class="mt-3 text-sm font-bold text-primary tracking-wide">{{ props.transfer.transfer_code
                                }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Scan untuk melakukan pembelian</p>
                        </div>
                    </div>

                    <!-- Transfer Details -->
                    <div class="px-6 py-5">
                        <div class="grid grid-cols-2 gap-y-3 text-sm bg-gray-50 p-4 rounded-core">
                            <span class="text-gray-500">Kode Transfer</span>
                            <span class="text-right font-semibold text-teal-600">{{ props.transfer.transfer_code
                                }}</span>

                            <span class="text-gray-500">Batch</span>
                            <span class="text-right font-semibold text-gray-900">{{ props.transfer.batch_code }}</span>

                            <span class="text-gray-500">POO</span>
                            <span class="text-right font-semibold text-gray-900">{{ props.transfer.poo_name }}</span>

                            <span class="text-gray-500">Volume</span>
                            <span class="text-right font-semibold text-gray-900">{{ props.transfer.volume }}
                                Liter</span>

                            <!-- <span class="text-gray-500">Penerima</span>
                            <span class="text-right font-semibold text-gray-900">{{ props.transfer.receiver_name
                                }}</span>

                            <template v-if="props.transfer.receiver_company">
                                <span class="text-gray-500">Perusahaan</span>
                                <span class="text-right font-semibold text-gray-900">{{ props.transfer.receiver_company
                                    }}</span>
                            </template> -->

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
                        <Button variant="outline" @click="transferBaru"
                            class="w-full text-gray-700 font-medium rounded border-gray-200 hover:border-primary hover:text-primary transition">
                            Kembali
                        </Button>
                        <Button  @click="downloadQR"
                            class="w-full bg-primary hover:bg-primary-hover text-white font-medium rounded">
                            <Download class="mr-1.5 h-4 w-4" />
                            Download QR
                        </Button>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
