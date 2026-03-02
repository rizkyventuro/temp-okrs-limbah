<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { ArrowLeft, Download, Lock } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';

interface FinalExport {
    id: string;
    batch_code: string;
    poo_name: string;
    volume: number;
    exported_at: string;
    refinery_name: string;
    iscc_document_url: string;

    // ISCC document data
    iscc: {
        poo_name: string;
        poo_street: string;
        poo_city: string;
        poo_country: string;
        poo_phone: string;
        uco_amount: string;
        recipient: string;
        signatory: string;
        place_date: string;
    };
}

const props = defineProps<{
    export: FinalExport;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Penjualan / Export', href: '/exports' },
    { title: 'Export Berhasil', href: '#' },
];

const formatDate = (d: string) =>
    new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

const downloadPDF = () => {
    window.open(props.export.iscc_document_url, '_blank');
};

const selesai = () => {
    router.visit('/exports');
};
</script>

<template>

    <Head title="Export Berhasil - FINAL LOCKED" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 items-center">
            <div class="w-full max-w-2xl flex flex-col gap-5">

                <!-- Back -->
                <button @click="selesai"
                    class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition w-fit">
                    <ArrowLeft class="h-4 w-4" />
                    Kembali
                </button>

                <!-- Card -->
                <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">

                    <!-- Header status -->
                    <div class="px-6 pt-6 pb-5 border-b border-gray-100">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-red-100">
                                <Lock class="h-4 w-4 text-red-500" />
                            </div>
                            <h1 class="text-[16px] font-bold text-gray-900">Export Berhasil — Status FINAL LOCKED</h1>
                        </div>
                        <p class="text-[13px] text-gray-500">
                            Batch <span class="font-semibold text-teal-600">{{ props.export.batch_code }}</span>
                            telah di-lock dan tidak dapat diubah. Dokumen ISCC siap diunduh.
                        </p>
                    </div>

                    <!-- ISCC Document Preview -->
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="rounded-xl border border-gray-200 bg-gray-50 overflow-hidden">
                            <!-- Doc header -->
                            <div class="border-b border-gray-200 bg-white px-5 py-3 flex items-center justify-between">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Preview Dokumen
                                    ISCC</p>
                                <span
                                    class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-[10px] font-bold text-red-600 uppercase tracking-wider">
                                    Final Locked
                                </span>
                            </div>

                            <!-- Document body mockup -->
                            <div
                                class="bg-white mx-4 my-4 rounded-lg border border-gray-200 shadow-sm p-5 text-xs text-gray-700 font-serif">
                                <p class="text-center text-[10px] text-gray-400 mb-3">pt-tambah-logo to:
                                    www.iscc-system.org</p>

                                <p class="text-center font-bold text-sm mb-1 leading-snug">
                                    ISCC CORSIA Self-Declaration for Points of Origin Generating Used Cooking Oil (UCO)
                                </p>

                                <!-- Table -->
                                <table class="w-full border border-gray-300 mt-4 text-xs">
                                    <tbody>
                                        <tr class="border-b border-gray-200 bg-gray-50">
                                            <td colspan="2" class="px-3 py-2 font-semibold text-gray-600 text-[10px]">
                                                Information about the Point of Origin (e.g. restaurant, catering
                                                facility, etc.)
                                            </td>
                                        </tr>
                                        <tr class="border-b border-gray-200">
                                            <td class="px-3 py-2 text-gray-500 w-1/3">Name</td>
                                            <td class="px-3 py-2 font-medium">{{ props.export.iscc.poo_name }}</td>
                                        </tr>
                                        <tr class="border-b border-gray-200">
                                            <td class="px-3 py-2 text-gray-500">Street address</td>
                                            <td class="px-3 py-2">{{ props.export.iscc.poo_street }}</td>
                                        </tr>
                                        <tr class="border-b border-gray-200">
                                            <td class="px-3 py-2 text-gray-500">Postcode, location</td>
                                            <td class="px-3 py-2">{{ props.export.iscc.poo_city }}</td>
                                        </tr>
                                        <tr class="border-b border-gray-200">
                                            <td class="px-3 py-2 text-gray-500">Country</td>
                                            <td class="px-3 py-2">{{ props.export.iscc.poo_country }}</td>
                                        </tr>
                                        <tr class="border-b border-gray-200">
                                            <td class="px-3 py-2 text-gray-500">Phone number</td>
                                            <td class="px-3 py-2">{{ props.export.iscc.poo_phone }}</td>
                                        </tr>
                                        <tr class="border-b border-gray-200">
                                            <td class="px-3 py-2 text-gray-500">The amount of UCO</td>
                                            <td class="px-3 py-2 font-semibold">{{ props.export.iscc.uco_amount }}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-2 text-gray-500">Recipient of the UCO (Collecting Point)
                                            </td>
                                            <td class="px-3 py-2 font-medium">{{ props.export.iscc.recipient }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Clause text excerpt -->
                                <div class="mt-3 rounded-lg bg-gray-50 border border-gray-200 px-3 py-2.5">
                                    <p class="text-[10px] text-gray-500 leading-relaxed">
                                        By signing this self-declaration, the signatory acknowledges and confirms the
                                        following:<br />
                                        1. UCO refers to oil out of vegetable or animal origin which has been used to
                                        cook food for human consumption...
                                        <span class="text-teal-500 font-medium cursor-pointer"> (selengkapnya di dokumen
                                            PDF)</span>
                                    </p>
                                </div>

                                <!-- Signature row -->
                                <table class="w-full border border-gray-300 mt-3 text-xs">
                                    <tbody>
                                        <tr>
                                            <td class="px-3 py-2.5 border-r border-gray-300 text-gray-500 w-1/3">Place,
                                                date</td>
                                            <td class="px-3 py-2.5 border-r border-gray-300 text-gray-500">Full name and
                                                function of signatory</td>
                                            <td class="px-3 py-2.5 text-gray-500">Signature</td>
                                        </tr>
                                        <tr class="border-t border-gray-200">
                                            <td class="px-3 py-3 text-gray-700">{{ props.export.iscc.place_date }}</td>
                                            <td class="px-3 py-3 text-gray-700">{{ props.export.iscc.signatory }}</td>
                                            <td class="px-3 py-3 italic text-gray-400">— (digital)</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- ISCC Logo footer -->
                                <div class="mt-4 flex items-center justify-between border-t border-gray-200 pt-3">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="h-6 w-6 rounded-full border-2 border-teal-600 flex items-center justify-center">
                                            <span class="text-[8px] font-bold text-teal-600">ISCC</span>
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-bold text-gray-700">ISCC System GmbH</p>
                                            <p class="text-[9px] text-gray-400">Version 2.1 as of: April 2024</p>
                                        </div>
                                    </div>
                                    <p class="text-[9px] text-gray-400">Copyright © ISCC System GmbH</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="grid grid-cols-2 gap-3 p-5">
                        <Button variant="outline" class="w-full text-gray-600 rounded border-gray-200 font-medium"
                            @click="selesai">
                            Selesai
                        </Button>
                        <Button @click="downloadPDF"
                            class="w-full bg-primary hover:bg-primary-hover text-white rounded font-medium">
                            <Download class="mr-2 h-4 w-4" />
                            Download PDF
                        </Button>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>