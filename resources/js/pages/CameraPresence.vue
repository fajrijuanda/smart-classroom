<template>
  <div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Presensi Mahasiswa</h1>

    <!-- BOX UTAMA -->
    <div class="bg-white shadow rounded p-4">
      <!-- Kamera + Popup -->
      <div class="flex flex-col items-center mb-4">
        <!-- Kamera -->
        <div class="w-[400px] h-[400px] bg-black rounded overflow-hidden relative">
          <img
            id="stream"
            :src="cameraUrl"
            crossorigin=""
            @load="onCameraLoad"
            @error="onCameraError"
            class="camera-stream"
          />
          <!-- Loading -->
          <div v-if="isLoading" class="absolute inset-0 flex items-center justify-center text-white">
            <span>Loading stream...</span>
          </div>
          <!-- Error -->
          <div
            v-if="cameraError"
            class="absolute inset-0 flex items-center justify-center text-red-500 bg-black/70 text-center px-4"
          >
            <p>Kamera tidak dapat diakses. Pastikan ESP32 aktif dan IP benar.</p>
          </div>
        </div>

        <!-- ✅ POPUP wrapper dengan tinggi tetap -->
        <div class="h-[60px] flex items-center justify-center mt-4">
          <transition name="fade">
            <div
              v-if="showPopup"
              class="bg-green-600 text-white px-10 py-3 rounded shadow-lg font-semibold text-lg flex items-center gap-2"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              Presensi Berhasil
            </div>
          </transition>
        </div>
      </div>

      <!-- FORM MAHASISWA -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-gray-700 text-sm font-medium">Nama</label>
          <input
            type="text"
            :value="student?.name ?? 'Belum terdeteksi'"
            readonly
            class="mt-1 w-full px-3 py-2 border border-gray-300 rounded bg-gray-100"
          />
        </div>
        <div>
          <label class="block text-gray-700 text-sm font-medium">Kelas</label>
          <input
            type="text"
            :value="student?.class ?? '-'"
            readonly
            class="mt-1 w-full px-3 py-2 border border-gray-300 rounded bg-gray-100"
          />
        </div>
        <div>
          <label class="block text-gray-700 text-sm font-medium">NIM</label>
          <input
            type="text"
            :value="student?.nim ?? '-'"
            readonly
            class="mt-1 w-full px-3 py-2 border border-gray-300 rounded bg-gray-100"
          />
        </div>
        <div>
          <label class="block text-gray-700 text-sm font-medium">Terakhir Hadir</label>
          <input
            type="text"
            :value="student?.last_seen ?? '-'"
            readonly
            class="mt-1 w-full px-3 py-2 border border-gray-300 rounded bg-gray-100"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'

const cameraUrl = ref('http://192.168.1.7:81/stream') // Ganti IP sesuai ESP32-mu
const isLoading = ref(true)
const cameraError = ref(false)
const showPopup = ref(false)
const student = ref(null)
let pollingInterval = null

const fetchDetection = async () => {
  try {
    const res = await fetch('/api/detect')
    const data = await res.json()

    if (data.name) {
      student.value = {
        name: data.name,
        nim: data.nim,
        class: data.class,
        last_seen: data.last_seen,
      }
      showPopup.value = true
      setTimeout(() => (showPopup.value = false), 3000)
    }
  } catch (err) {
    console.error('API error:', err)
  }
}

const onCameraLoad = () => {
  isLoading.value = false
  cameraError.value = false
}

const onCameraError = () => {
  isLoading.value = false
  cameraError.value = true
}

onMounted(() => {
  pollingInterval = setInterval(fetchDetection, 5000)
})

onBeforeUnmount(() => {
  clearInterval(pollingInterval)
})
</script>

<style scoped>
.camera-stream {
  width: 100%;
  height: 100%;
  object-fit: contain;
  transform: rotate(90deg);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
