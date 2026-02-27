<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { ArrowLeft, QrCode } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

interface Batch {
    id: string;
    code: string;
    poo_name: string;
    volume: number;
}

const props = defineProps<{
    batches: Batch[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Transfer UCO',
        href: '/transfers',
    },
    {
        title: 'Kirim UCO',
        href: '/transfers/create',
    },
];

const form = useForm({
    batch_id: '',
    recipient_name: '',
    recipient_company: '',
});

const handleSubmit = () => {
    form.post('/transfers', {
        onError: () => {
            toast.error('Gagal!', {
                description: 'Terjadi kesalahan saat memproses transfer',
            });
        },
    });
};

const goBack = () => {
    router.visit('/transfers');
};
</script>

<template>
    <Head title="Kirim UCO" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 items-center">
            <div class="w-full max-w-xl flex flex-col gap-6">

                <!-- Back -->
                <button
                    @click="goBack"
                    class="flex items-center gap-1.5 text-sm text-gray-500 hover: transition w-fit"
                >
                    <ArrowLeft class="h-4 w-4" />
                    Kembali
                </button>

                <!-- Card -->
                <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                    <!-- Card Header -->
                    <div class="px-6 pt-6 pb-4 border-b border-gray-100">
                        <h1 class="text-[16px] font-bold text-gray-900">Kirim / Transfer UCO</h1>
                        <p class="text-[13px] text-gray-500">Pilih batch dan isi data penerima</p>
                    </div>

                    <!-- Form -->
                    <div class="grid gap-5 p-6">

                        <!-- Pilih Batch UCO -->
                        <div class="grid gap-1.5">
                            <Label class="text-sm font-bold ">
                                Pilih Batch UCO <span class="text-red-500">*</span>
                            </Label>
                            <Select v-model="form.batch_id">
                                <SelectTrigger
                                    class="border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary-surface w-full rounded rounded-core"
                                    :class="{ 'border-red-400': form.errors.batch_id }"
                                >
                                    <SelectValue placeholder="Batch UCO" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="batch in props.batches"
                                        :key="batch.id"
                                        :value="String(batch.id)"
                                    >
                                        {{ batch.code }} — {{ batch.poo_name }} • {{ batch.volume }} L
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <span v-if="form.errors.batch_id" class="text-xs text-red-500">{{ form.errors.batch_id }}</span>
                        </div>

                        <!-- Nama Penerima -->
                        <div class="grid gap-1.5">
                            <Label class="text-sm font-bold ">
                                Nama Penerima <span class="text-red-500">*</span>
                            </Label>
                            <Input
                                v-model="form.recipient_name"
                                placeholder="Nama pengepul penerima"
                                class="border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary-surface"
                                :class="{ 'border-red-400': form.errors.recipient_name }"
                            />
                            <span v-if="form.errors.recipient_name" class="text-xs text-red-500">{{ form.errors.recipient_name }}</span>
                        </div>

                        <!-- Perusahaan / Usaha -->
                        <div class="grid gap-1.5">
                            <Label class="text-sm font-bold ">Perusahaan / Usaha</Label>
                            <Input
                                v-model="form.recipient_company"
                                placeholder="Nama perusahaan penerima"
                                class="border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary-surface"
                            />
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="px-6 pb-6">
                        <Button
                            @click="handleSubmit"
                            :disabled="form.processing"
                            class="w-full bg-primary hover:bg-primary-hover text-white font-medium rounded py-2.5"
                        >
                            <QrCode class="mr-2 h-4 w-4" />
                            Generate QR
                        </Button>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>