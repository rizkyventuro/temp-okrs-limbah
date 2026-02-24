<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

const goBack = () => window.history.back();
</script>

<template>
    <Head title="404 - Halaman Tidak Ditemukan" />
    
    <div class="min-h-screen bg-gradient-to-br from-[#1565C0] via-[#1976D2] to-[#1E88E5] flex items-center justify-center px-4 overflow-hidden relative">
        <!-- Animated Background -->
        <div class="absolute inset-0 overflow-hidden">
            <!-- Flight path lines -->
            <div class="absolute inset-0">
                <svg class="w-full h-full opacity-10" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>
            <!-- Floating particles -->
            <div
                v-for="i in 15"
                :key="i"
                class="absolute rounded-full bg-white/5 blur-sm animate-pulse"
                :style="{
                    left: `${Math.random() * 100}%`,
                    top: `${Math.random() * 100}%`,
                    width: `${20 + Math.random() * 40}px`,
                    height: `${20 + Math.random() * 40}px`,
                    animationDelay: `${Math.random() * 3}s`,
                    animationDuration: `${3 + Math.random() * 2}s`
                }"
            />
        </div>

        <div class="max-w-3xl w-full text-center relative z-10">
            <!-- Logo/Brand Section -->
            <div class="mb-8 animate-[fadeIn_0.6s_ease-out]">
                <div class="inline-flex items-center gap-3 bg-white/10 backdrop-blur-md px-6 py-3 rounded-full border border-white/20">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                    </svg>
                    <span class="text-white font-bold text-xl tracking-wide">AirNav Indonesia</span>
                </div>
            </div>

            <!-- 404 Number -->
            <div class="relative mb-6">
                <div class="text-[160px] md:text-[200px] font-black text-white/10 leading-none select-none animate-[fadeIn_0.8s_ease-out]">
                    404
                </div>
                <!-- Train Icon Overlay -->
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    <div class="animate-[wiggle_2s_ease-in-out_infinite]">
                        <svg class="w-28 h-28 md:w-36 md:h-36 text-white drop-shadow-2xl" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2c-4 0-8 .5-8 4v9.5C4 17.43 5.57 19 7.5 19L6 20.5v.5h2l2-2h4l2 2h2v-.5L16.5 19c1.93 0 3.5-1.57 3.5-3.5V6c0-3.5-4-4-8-4zM7.5 17c-.83 0-1.5-.67-1.5-1.5S6.67 14 7.5 14s1.5.67 1.5 1.5S8.33 17 7.5 17zm3.5-7H6V6h5v4zm2 0V6h5v4h-5zm3.5 7c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Text Content -->
            <div class="space-y-4 mb-10 animate-[fadeInUp_0.6s_ease-out_0.3s_both]">
                <h1 class="text-4xl md:text-5xl font-bold text-white">
                    Halaman Tidak Ditemukan
                </h1>
                <p class="text-lg md:text-xl text-white/90 max-w-xl mx-auto leading-relaxed">
                    Maaf, kereta yang Anda cari telah berangkat ke tujuan yang tidak diketahui.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-[fadeInUp_0.6s_ease-out_0.5s_both]">
                <Link href="/">
                    <button class="group inline-flex items-center gap-3 bg-white text-[#1565C0] px-8 py-4 rounded-xl font-bold text-lg shadow-xl hover:shadow-2xl hover:scale-105 active:scale-95 transition-all">
                        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Kembali ke Beranda
                    </button>
                </Link>

                <button
                    @click="goBack"
                    class="group inline-flex items-center gap-3 bg-white/10 backdrop-blur-md text-white px-8 py-4 rounded-xl font-semibold text-lg border-2 border-white/30 hover:bg-white/20 hover:border-white/50 hover:scale-105 active:scale-95 transition-all"
                >
                    <svg class="w-5 h-5 group-hover:-rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Halaman Sebelumnya
                </button>
            </div>

            <!-- Additional Info -->
            <div class="mt-12 space-y-2 animate-[fadeIn_0.8s_ease-out_0.8s_both]">
                <div class="inline-flex items-center gap-2 bg-white/5 backdrop-blur-sm px-4 py-2 rounded-full border border-white/10">
                    <span class="text-white/60 text-sm font-mono">Error Code: 404</span>
                    <span class="text-white/40">|</span>
                    <span class="text-white/60 text-sm">Page Not Found</span>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes wiggle {
    0%, 100% { transform: translateX(0) rotate(0deg); }
    25% { transform: translateX(10px) rotate(2deg); }
    50% { transform: translateX(0) rotate(0deg); }
    75% { transform: translateX(-10px) rotate(-2deg); }
}
</style>
