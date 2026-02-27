<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ArrowLeft, Camera, MapPin, Store } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import BadgeBussines from '@/components/BadgeBussines.vue';

interface POO {
    id: number;
    name: string;
    address: string;
    type: 'Restoran' | 'UMKM' | 'Rumah Tangga';
}

const props = defineProps<{
    poo: POO;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Pengambilan dari POO', href: '/poos/' },
    { title: 'Catat Pengambilan', href: '#' },
];

const photoPreview = ref<string | null>(null);
const fileInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    poo_id: props.poo.id,
    volume: '',
    collection_date: new Date().toISOString().split('T')[0],
    photo: null as File | null,
    notes: '',
});

const today = new Date().toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
});

const handlePhotoChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
        form.photo = file;
        const reader = new FileReader();
        reader.onload = (ev) => {
            photoPreview.value = ev.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

const triggerUpload = () => {
    fileInput.value?.click();
};

const handleSubmit = () => {
    form.post('/batches', {
        onError: () => {
            toast.error('Gagal!', { description: 'Terjadi kesalahan saat menyimpan data' });
        },
    });
};

</script>

<template>

    <Head title="Catat Pengambilan UCO" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 items-center">
            <div class="w-full max-w-xl flex flex-col gap-5">

                <!-- Back -->
                <button @click="router.visit('/poos/')"
                    class="flex items-center gap-1.5 text-sm text-gray-500 hover: transition w-fit">
                    <ArrowLeft class="h-4 w-4" />
                    Kembali
                </button>

                <!-- Card -->
                <div class="rounded-2xl border border-[#EDEDED] bg-white shadow-sm overflow-hidden">

                    <!-- Header -->
                    <div class="px-6 pt-6 pb-4 border-b border-gray-100">
                        <h1 class="text-[16px] font-bold text-gray-900">Catat Pengambilan UCO</h1>
                        <p class="text-[13px] text-gray-500 mt-0.5">Isi detail pengambilan minyak jelantah</p>
                    </div>

                    <div class="grid gap-5 p-6">

                        <!-- POO Terpilih -->
                        <div class="rounded-xl border border-primary bg-primary-surface p-4">
                            <p class="text-xs font-semibold text-primary mb-2">POO Terpilih</p>
                            <p class="text-sm font-bold text-gray-900">{{ props.poo.name }}</p>
                            <div class="flex items-start gap-1.5 mt-1">
                                <MapPin class="h-3.5 w-3.5 text-gray-400 mt-0.5 flex-shrink-0" />
                                <p class="text-xs text-gray-500">{{ props.poo.address }}</p>
                            </div>
                            <div class="mt-2">
                                <BadgeBussines :type="poo.type" />
                            </div>
                        </div>

                        <!-- Volume -->
                        <div class="grid gap-1.5">
                            <Label class="text-sm font-bold ">
                                Volume Minyak (Liter) <span class="text-red-500">*</span>
                            </Label>
                            <Input v-model="form.volume" type="number" placeholder="Contoh: 25"
                                class="border-[#EDEDED] focus:border-primary-hover focus:ring-2 focus:ring-teal-100"
                                :class="{ 'border-red-400': form.errors.volume }" />
                            <span v-if="form.errors.volume" class="text-xs text-red-500">{{ form.errors.volume }}</span>
                        </div>

                        <!-- Tanggal Pengambilan -->
                        <div class="grid gap-1.5">
                            <Label class="text-sm font-bold ">
                                Tanggal Pengambilan <span class="text-red-500">*</span>
                            </Label>
                            <Input v-model="form.collection_date" type="date"
                                class="border-[#EDEDED] focus:border-primary-hover focus:ring-2 focus:ring-teal-100"
                                :class="{ 'border-red-400': form.errors.collection_date }" />
                            <span v-if="form.errors.collection_date" class="text-xs text-red-500">{{
                                form.errors.collection_date }}</span>
                        </div>

                        <!-- Foto Pengambilan -->
                        <div class="grid gap-1.5">
                            <Label class="text-sm font-bold ">Foto Pengambilan</Label>
                            <input ref="fileInput" type="file" accept="image/*" class="hidden"
                                @change="handlePhotoChange" />
                            <button @click="triggerUpload" type="button"
                                class="w-full rounded-xl border-2 border-dashed border-[#D6D6D6] bg-gray-50 py-8 flex flex-col items-center justify-center gap-2 hover:border-teal-300 hover:bg-teal-50 transition cursor-pointer overflow-hidden">
                                <template v-if="photoPreview">
                                    <img :src="photoPreview" class="max-h-40 rounded-lg object-contain" />
                                    <span class="text-xs text-gray-400 mt-1">Klik untuk ganti foto</span>
                                </template>
                                <template v-else>
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                                        <Camera class="h-5 w-5 text-gray-400" />
                                    </div>
                                    <span class="text-sm font-bold text-gray-500">Upload</span>
                                    <span class="text-xs text-gray-400">Foto saat pengambilan (opsional)</span>
                                </template>
                            </button>
                        </div>

                        <!-- Catatan -->
                        <div class="grid gap-1.5">
                            <Label class="text-sm font-bold ">Catatan</Label>
                            <textarea v-model="form.notes" placeholder="Catatan tambahan (opsional)..." rows="3"
                                class="w-full rounded-core border border-[#EDEDED] px-3 py-2.5 text-sm placeholder-gray-400 focus:border-primary-hover focus:outline-none focus:ring-2 focus:ring-teal-100" />
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="px-6 pb-6">
                        <Button @click="handleSubmit" :disabled="form.processing"
                            class="w-full bg-primary hover:bg-primary-hover text-white font-medium rounded py-2.5">
                            Simpan &amp; Generate QR
                        </Button>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>