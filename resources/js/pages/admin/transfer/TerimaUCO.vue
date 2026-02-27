<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { ArrowLeft, ScanLine, CheckCircle2, XCircle } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import QrScanner from '@/components/QrScanner.vue';

interface TransferData {
    id: string;
    transfer_code: string;
    batch_code: string;
    poo_name: string;
    volume: number;
    receiver_name: string;
    status: string;
    status_code: number;
}

const props = defineProps<{
    transfer?: TransferData | null;
    searchCode?: string | null;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Transfer UCO', href: '/transfers' },
    { title: 'Terima UCO', href: '#' },
];

const isScannerOpen = ref(false);
const manualCode = ref(props.searchCode ?? '');

const form = useForm({
    transfer_code: props.transfer?.transfer_code ?? '',
});

watch(() => props.transfer, (val) => {
    if (val) {
        form.transfer_code = val.transfer_code;
    }
});

const openCamera = () => {
    isScannerOpen.value = true;
};

const onQrDetected = (code: string) => {
    isScannerOpen.value = false;
    manualCode.value = code;
    toast.success('QR Terdeteksi!', { description: `Kode: ${code}` });
    // Search for the transfer
    router.get('/transfers/claim', { code }, { preserveState: false });
};

const onScannerClose = () => {
    isScannerOpen.value = false;
};

const handleSearch = () => {
    if (!manualCode.value.trim()) {
        toast.error('Masukkan kode transfer terlebih dahulu');
        return;
    }
    router.get('/transfers/claim', { code: manualCode.value.trim() }, { preserveState: false });
};

const handleTerima = () => {
    if (!form.transfer_code) return;

    form.post('/transfers/claim', {
        onSuccess: () => {
            toast.success('Berhasil!', { description: 'Kepemilikan batch berhasil diterima' });
        },
        onError: () => {
            toast.error('Gagal!', { description: 'Terjadi kesalahan saat menerima transfer' });
        },
    });
};
</script>

<template>

    <Head title="Terima Transfer UCO" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 items-center">
            <div class="w-full max-w-xl flex flex-col gap-5">

                <!-- Back -->
                <button @click="router.visit('/transfers')"
                    class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition w-fit">
                    <ArrowLeft class="h-4 w-4" />
                    Kembali
                </button>

                <!-- Card -->
                <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">

                    <!-- Header -->
                    <div class="px-6 pt-6 pb-4 border-b border-gray-100">
                        <h1 class="text-[16px] font-bold text-gray-900">Terima Transfer UCO</h1>
                        <p class="text-[13px] text-gray-500 mt-0.5">
                            Scan QR atau masukkan kode transfer untuk menerima kepemilikan
                        </p>
                    </div>

                    <div class="grid gap-5 p-6">

                        <!-- QR Scanner Component -->
                        <QrScanner :is-open="isScannerOpen" @detected="onQrDetected" @close="onScannerClose" />

                        <!-- Camera CTA (shown when scanner is closed) -->
                        <div v-if="!isScannerOpen"
                            class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 py-10 gap-3">
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-2xl border border-gray-200 bg-white shadow-sm text-teal-600">
                                <ScanLine class="h-7 w-7" />
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-semibold text-gray-700">Area Scan QR</p>
                                <p class="text-xs text-gray-400 mt-0.5">Arahkan kamera ke QR Code transfer</p>
                            </div>
                            <Button @click="openCamera"
                                class="mt-1 bg-primary hover:bg-primary-hover text-white text-sm font-medium px-5 py-2 rounded-lg">
                                Buka Kamera
                            </Button>
                        </div>

                        <!-- Divider -->
                        <div class="flex items-center gap-3">
                            <div class="h-px flex-1 bg-gray-100" />
                            <span class="text-xs text-gray-400">atau masukkan kode manual</span>
                            <div class="h-px flex-1 bg-gray-100" />
                        </div>

                        <!-- Manual Input -->
                        <div class="flex gap-2">
                            <Input v-model="manualCode" placeholder="Contoh: TRF-2026-0001"
                                class="flex-1 border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary-surface"
                                @keyup.enter="handleSearch" />
                            <Button @click="handleSearch"
                                class="bg-primary hover:bg-primary-hover text-white font-medium px-5 rounded shrink-0">
                                Cari
                            </Button>
                        </div>

                        <!-- Transfer found -->
                        <div v-if="props.transfer && props.transfer.status_code === 1"
                            class="rounded-xl border border-teal-200 bg-teal-50 p-4 space-y-3">
                            <div class="flex items-center gap-2">
                                <CheckCircle2 class="h-5 w-5 text-teal-600 flex-shrink-0" />
                                <p class="text-sm font-semibold text-teal-700">Transfer Ditemukan</p>
                            </div>
                            <div class="grid grid-cols-2 gap-y-2 text-sm">
                                <span class="text-gray-500">Kode Transfer</span>
                                <span class="text-right font-semibold text-teal-600">{{ props.transfer.transfer_code }}</span>

                                <span class="text-gray-500">Batch</span>
                                <span class="text-right font-semibold text-gray-900">{{ props.transfer.batch_code }}</span>

                                <span class="text-gray-500">Asal POO</span>
                                <span class="text-right font-semibold text-gray-900">{{ props.transfer.poo_name }}</span>

                                <span class="text-gray-500">Volume</span>
                                <span class="text-right font-semibold text-gray-900">{{ props.transfer.volume }} Liter</span>

                                <span class="text-gray-500">Status</span>
                                <span class="text-right">
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700">
                                        Pending
                                    </span>
                                </span>
                            </div>
                        </div>

                        <!-- Transfer already claimed or cancelled -->
                        <div v-else-if="props.transfer && props.transfer.status_code !== 1"
                            class="rounded-xl border border-red-200 bg-red-50 p-4 flex items-center gap-3">
                            <XCircle class="h-5 w-5 text-red-500 flex-shrink-0" />
                            <div>
                                <p class="text-sm font-semibold text-red-700">Transfer Tidak Tersedia</p>
                                <p class="text-xs text-red-500 mt-0.5">Transfer ini sudah diklaim atau dibatalkan.</p>
                            </div>
                        </div>

                        <!-- Not found -->
                        <div v-else-if="props.searchCode && !props.transfer"
                            class="rounded-xl border border-red-200 bg-red-50 p-4 flex items-center gap-3">
                            <XCircle class="h-5 w-5 text-red-500 flex-shrink-0" />
                            <div>
                                <p class="text-sm font-semibold text-red-700">Tidak Ditemukan</p>
                                <p class="text-xs text-red-500 mt-0.5">Kode transfer "{{ props.searchCode }}" tidak ditemukan.</p>
                            </div>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="px-6 pb-6">
                        <Button @click="handleTerima"
                            :disabled="form.processing || !props.transfer || props.transfer.status_code !== 1"
                            class="w-full bg-primary hover:bg-primary-hover text-white font-medium rounded py-2.5 disabled:opacity-50">
                            Terima Kepemilikan
                        </Button>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
