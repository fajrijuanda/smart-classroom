<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

// 1. Ganti layout yang diimpor menjadi AuthSimpleLayout
import AuthSimpleLayout from '@/layouts/auth/AuthSimpleLayout.vue';

// Import komponen UI Anda
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';


// --- Bagian script lainnya tidak ada perubahan ---
const form = useForm<{
    nim: string;
    phone: string;
    bio: string;
    address: string;
    website: string;
    images: string[];
}>({
    nim: '',
    phone: '',
    bio: '',
    address: '',
    website: '',
    images: [],
});

const video = ref<HTMLVideoElement | null>(null);
const canvas = ref<HTMLCanvasElement | null>(null);
const isCapturing = ref(false);
const captureProgress = ref(0);
let captureInterval: ReturnType<typeof setInterval> | null = null;
let stream: MediaStream | null = null;

const startCamera = async () => { try { stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false }); if (video.value) { video.value.srcObject = stream; await video.value.play(); } } catch (err) { console.error("Camera access error:", err); alert("Gagal mengakses kamera. Pastikan Anda telah memberikan izin pada browser."); } };
const stopCamera = () => { if (stream) { stream.getTracks().forEach(track => track.stop()); } };
const startCapture = () => { if (isCapturing.value || captureProgress.value >= 30 || !video.value) return; isCapturing.value = true; captureInterval = setInterval(() => { if (captureProgress.value >= 30 || !canvas.value || !video.value) { if (captureInterval) clearInterval(captureInterval); isCapturing.value = false; stopCamera(); return; } const context = canvas.value.getContext('2d'); if (context) { canvas.value.width = video.value.videoWidth; canvas.value.height = video.value.videoHeight; context.drawImage(video.value, 0, 0, canvas.value.width, canvas.value.height); form.images.push(canvas.value.toDataURL('image/jpeg')); captureProgress.value++; } }, 1000); };
const submit = () => { if (captureProgress.value < 30) { alert('Harap selesaikan proses pengambilan 30 gambar wajah.'); return; } form.post(route('profile.store')); };
onMounted(startCamera);
onUnmounted(stopCamera);

</script>

<template>

    <Head title="Complete Profile" />

    <AuthSimpleLayout title="Lengkapi Profil Anda"
        description="Selesaikan pendaftaran dengan mengisi data diri dan mengambil data wajah.">
        <form @submit.prevent="submit" class="space-y-6">

            <div class="grid gap-4">
                <div class="grid gap-1.5">
                    <Label for="nim">NIM (Nomor Induk Mahasiswa)</Label>
                    <Input id="nim" type="text" required v-model="form.nim" />
                    <InputError :message="form.errors.nim" />
                </div>
                <div class="grid gap-1.5">
                    <Label for="phone">Nomor Telepon</Label>
                    <Input id="phone" type="tel" required v-model="form.phone" />
                    <InputError :message="form.errors.phone" />
                </div>
                <div class="grid gap-1.5">
                    <Label for="address">Alamat</Label>
                    <Input id="address" type="text" v-model="form.address" />
                    <InputError :message="form.errors.address" />
                </div>
            </div>

            <div class="space-y-4">
                <Label>Pengambilan Data Wajah</Label>
                <div class="bg-secondary rounded-md overflow-hidden aspect-video relative">
                    <video ref="video" class="w-full h-full object-cover"></video>
                    <canvas ref="canvas" class="hidden"></canvas>
                    <div v-if="captureProgress > 0"
                        class="absolute top-2 right-2 bg-black/50 text-white text-sm px-2 py-1 rounded">
                        {{ captureProgress }} / 30
                    </div>
                </div>
                <div v-if="captureProgress < 30">
                    <Button type="button" @click="startCapture" :disabled="isCapturing" class="w-full">
                        <LoaderCircle v-if="isCapturing" class="mr-2 h-4 w-4 animate-spin" />
                        {{ isCapturing ? 'Sedang Merekam...' : 'Mulai Pengambilan Wajah' }}
                    </Button>
                </div>
                <div v-else
                    class="text-center p-3 text-sm bg-green-100 text-green-800 rounded-lg dark:bg-green-900/50 dark:text-green-300">
                    ✅ Perekaman wajah selesai.
                </div>
            </div>

            <div class="flex justify-end">
                <Button type="submit" :disabled="form.processing || captureProgress < 30" class="w-full">
                    <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                    Simpan Profil & Lanjutkan
                </Button>
            </div>
        </form>
    </AuthSimpleLayout>
</template>