<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { Camera, X, ZoomIn } from 'lucide-vue-next';

const props = defineProps<{
    isOpen: boolean;
}>();

const emit = defineEmits<{
    (e: 'detected', code: string): void;
    (e: 'close'): void;
}>();

const videoRef = ref<HTMLVideoElement | null>(null);
const canvasRef = ref<HTMLCanvasElement | null>(null);
const overlayCanvasRef = ref<HTMLCanvasElement | null>(null);
const stream = ref<MediaStream | null>(null);
const scanning = ref(false);
const zoomLevel = ref(1);
const detectedCode = ref('');
const scanStatus = ref<'idle' | 'scanning' | 'found'>('idle');
const errorMsg = ref('');
const animationId = ref<number | null>(null);

// jsQR will be loaded dynamically
let jsQR: any = null;

const loadJsQR = async () => {
    if (jsQR) return jsQR;
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jsqr/1.4.0/jsQR.min.js';
        script.onload = () => {
            jsQR = (window as any).jsQR;
            resolve(jsQR);
        };
        script.onerror = reject;
        document.head.appendChild(script);
    });
};

const startCamera = async () => {
    errorMsg.value = '';
    try {
        await loadJsQR();

        const constraints: MediaStreamConstraints = {
            video: {
                facingMode: 'environment',
                width: { ideal: 1280 },
                height: { ideal: 720 },
            },
        };

        stream.value = await navigator.mediaDevices.getUserMedia(constraints);

        if (videoRef.value) {
            videoRef.value.srcObject = stream.value;
            await videoRef.value.play();
            scanning.value = true;
            scanStatus.value = 'scanning';
            requestAnimationFrame(scanFrame);
        }
    } catch (err: any) {
        if (err.name === 'NotAllowedError') {
            errorMsg.value = 'Akses kamera ditolak. Mohon izinkan akses kamera di pengaturan browser.';
        } else if (err.name === 'NotFoundError') {
            errorMsg.value = 'Kamera tidak ditemukan pada perangkat ini.';
        } else {
            errorMsg.value = 'Gagal membuka kamera. Coba refresh halaman.';
        }
    }
};

const stopCamera = () => {
    if (animationId.value) {
        cancelAnimationFrame(animationId.value);
        animationId.value = null;
    }
    if (stream.value) {
        stream.value.getTracks().forEach((t) => t.stop());
        stream.value = null;
    }
    scanning.value = false;
    zoomLevel.value = 1;
    scanStatus.value = 'idle';
    detectedCode.value = '';
};

// Auto-zoom: analyze QR bounding box size relative to frame
const computeZoom = (location: any, videoWidth: number, videoHeight: number): number => {
    if (!location) return 1;

    const pts = [
        location.topLeftCorner,
        location.topRightCorner,
        location.bottomRightCorner,
        location.bottomLeftCorner,
    ];

    const xs = pts.map((p: any) => p.x);
    const ys = pts.map((p: any) => p.y);
    const qrW = Math.max(...xs) - Math.min(...xs);
    const qrH = Math.max(...ys) - Math.min(...ys);

    // Target: QR should occupy ~50% of the shorter dimension
    const targetFraction = 0.5;
    const frameShorter = Math.min(videoWidth, videoHeight);
    const qrAvg = (qrW + qrH) / 2;

    const desired = (targetFraction * frameShorter) / qrAvg;
    // Clamp between 1x and 4x
    return Math.min(Math.max(desired, 1), 4);
};

const drawOverlay = (location: any | null) => {
    const canvas = overlayCanvasRef.value;
    const video = videoRef.value;
    if (!canvas || !video) return;

    canvas.width = video.clientWidth;
    canvas.height = video.clientHeight;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    if (!location) return;

    const scaleX = canvas.width / video.videoWidth;
    const scaleY = canvas.height / video.videoHeight;

    const pts = [
        location.topLeftCorner,
        location.topRightCorner,
        location.bottomRightCorner,
        location.bottomLeftCorner,
    ];

    ctx.beginPath();
    ctx.moveTo(pts[0].x * scaleX, pts[0].y * scaleY);
    pts.slice(1).forEach((p: any) => ctx.lineTo(p.x * scaleX, p.y * scaleY));
    ctx.closePath();
    ctx.strokeStyle = '#00BFA5';
    ctx.lineWidth = 3;
    ctx.stroke();
    ctx.fillStyle = 'rgba(0, 191, 165, 0.15)';
    ctx.fill();
};

const scanFrame = () => {
    const video = videoRef.value;
    const canvas = canvasRef.value;
    if (!video || !canvas || !scanning.value) return;

    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d', { willReadFrequently: true });
        if (ctx) {
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);

            const code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: 'dontInvert',
            });

            if (code) {
                scanStatus.value = 'found';
                detectedCode.value = code.data;

                // Auto zoom
                const newZoom = computeZoom(code.location, canvas.width, canvas.height);
                zoomLevel.value = newZoom;

                drawOverlay(code.location);

                // Emit after short delay so user sees the highlight
                setTimeout(() => {
                    emit('detected', code.data);
                    stopCamera();
                }, 800);
                return;
            } else {
                drawOverlay(null);
                // Slowly return zoom to 1 if no QR found
                if (zoomLevel.value > 1) {
                    zoomLevel.value = Math.max(1, zoomLevel.value - 0.05);
                }
            }
        }
    }

    animationId.value = requestAnimationFrame(scanFrame);
};

watch(
    () => props.isOpen,
    (val) => {
        if (val) {
            startCamera();
        } else {
            stopCamera();
        }
    },
);

onUnmounted(() => {
    stopCamera();
});
</script>

<template>
    <div v-if="isOpen" class="flex flex-col gap-4">

        <!-- Viewport -->
        <div class="relative w-full overflow-hidden rounded-2xl bg-black" style="aspect-ratio: 4/3">

            <!-- Video -->
            <video ref="videoRef"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 ease-out origin-center"
                :style="{ transform: `scale(${zoomLevel})` }" muted playsinline autoplay />

            <!-- Hidden canvas for jsQR processing -->
            <canvas ref="canvasRef" class="hidden" />

            <!-- Overlay canvas for QR highlight -->
            <canvas ref="overlayCanvasRef" class="absolute inset-0 w-full h-full pointer-events-none" />

            <!-- Scan frame guide -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="relative h-52 w-52">
                    <!-- Corners -->
                    <span class="absolute top-0 left-0 h-8 w-8 border-t-4 border-l-4 rounded-tl-lg"
                        :class="scanStatus === 'found' ? 'border-green-400' : 'border-white'" />
                    <span class="absolute top-0 right-0 h-8 w-8 border-t-4 border-r-4 rounded-tr-lg"
                        :class="scanStatus === 'found' ? 'border-green-400' : 'border-white'" />
                    <span class="absolute bottom-0 left-0 h-8 w-8 border-b-4 border-l-4 rounded-bl-lg"
                        :class="scanStatus === 'found' ? 'border-green-400' : 'border-white'" />
                    <span class="absolute bottom-0 right-0 h-8 w-8 border-b-4 border-r-4 rounded-br-lg"
                        :class="scanStatus === 'found' ? 'border-green-400' : 'border-white'" />

                    <!-- Scan line animation -->
                    <div v-if="scanStatus === 'scanning'"
                        class="absolute left-2 right-2 h-0.5 bg-teal-400 opacity-80 rounded scan-line" />
                </div>
            </div>

            <!-- Zoom indicator -->
            <div v-if="zoomLevel > 1.05"
                class="absolute top-3 right-3 flex items-center gap-1 rounded-full bg-black/50 px-2.5 py-1 text-xs text-white backdrop-blur">
                <ZoomIn class="h-3 w-3" />
                {{ zoomLevel.toFixed(1) }}x
            </div>

            <!-- Status label -->
            <div class="absolute bottom-3 left-1/2 -translate-x-1/2">
                <span v-if="scanStatus === 'scanning'"
                    class="rounded-full bg-black/50 px-3 py-1 text-xs text-white backdrop-blur">
                    Arahkan ke QR Code batch UCO
                </span>
                <span v-else-if="scanStatus === 'found'"
                    class="rounded-full bg-green-500/80 px-3 py-1 text-xs text-white backdrop-blur font-semibold">
                    QR Terdeteksi!
                </span>
            </div>

            <!-- Error state -->
            <div v-if="errorMsg"
                class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900 text-center px-6">
                <Camera class="h-10 w-10 text-gray-500 mb-3" />
                <p class="text-sm text-gray-300">{{ errorMsg }}</p>
            </div>
        </div>

        <!-- Close button -->
        <button @click="$emit('close')"
            class="flex items-center justify-center gap-2 rounded-xl border border-gray-200 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
            <X class="h-4 w-4" />
            Tutup Kamera
        </button>
    </div>
</template>

<style scoped>
@keyframes scan {
    0% {
        top: 8px;
        opacity: 1;
    }

    50% {
        opacity: 0.6;
    }

    100% {
        top: calc(100% - 8px);
        opacity: 1;
    }
}

.scan-line {
    animation: scan 2s ease-in-out infinite alternate;
}
</style>