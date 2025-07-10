<template>
  <div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Presensi Mahasiswa</h1>

    <!-- CAMERA BOX -->
    <div class="bg-white shadow rounded p-4">
      <div class="relative aspect-video bg-black rounded overflow-hidden mb-6">
        <!-- Kamera -->
        <img v-if="cameraReady" :src="cameraUrl" alt="Live Camera Stream"
          class="w-full h-full object-cover transform scale-x-[-1]" @error="onCameraError" />

        <!-- Loading -->
        <div v-if="isLoading" class="absolute inset-0 flex items-center justify-center text-white">
          <span>Loading stream...</span>
        </div>

        <!-- Error -->
        <div v-if="cameraError"
          class="absolute inset-0 flex items-center justify-center text-red-500 bg-black/70 text-center px-4">
          <p>Kamera tidak dapat diakses. Pastikan ESP32 aktif dan IP benar.</p>
        </div>

        <!-- Popup -->
        <transition name="fade">
          <div v-if="showPopup" class="absolute bottom-4 left-1/2 transform -translate-x-1/2
           bg-green-600 text-white px-6 py-3 rounded shadow-lg font-semibold text-lg flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Presensi Berhasil
          </div>
        </transition>
      </div>

      <!-- FORM INFO MAHASISWA -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-gray-700 text-sm font-medium">Nama</label>
          <input type="text" :value="student?.name ?? 'Belum terdeteksi'" readonly
            class="mt-1 w-full px-3 py-2 border border-gray-300 rounded bg-gray-100" />
        </div>

        <div>
          <label class="block text-gray-700 text-sm font-medium">Kelas</label>
          <input type="text" :value="student?.class ?? '-'" readonly
            class="mt-1 w-full px-3 py-2 border border-gray-300 rounded bg-gray-100" />
        </div>

        <div>
          <label class="block text-gray-700 text-sm font-medium">NIM</label>
          <input type="text" :value="student?.nim ?? '-'" readonly
            class="mt-1 w-full px-3 py-2 border border-gray-300 rounded bg-gray-100" />
        </div>

        <div>
          <label class="block text-gray-700 text-sm font-medium">Terakhir Hadir</label>
          <input type="text" :value="student?.last_seen ?? '-'" readonly
            class="mt-1 w-full px-3 py-2 border border-gray-300 rounded bg-gray-100" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'

const cameraUrl = ref('http://<ESP32-IP>/stream') // Ganti dengan IP ESP32 kamu
const cameraReady = ref(false)
const cameraError = ref(false)
const isLoading = ref(true)

const showPopup = ref(false)
const student = ref(null)

let pollingInterval = null

const checkCamera = () => {
  const testImg = new Image()
  testImg.src = cameraUrl.value + '?rand=' + Math.random()
  testImg.onload = () => {
    cameraReady.value = true
    cameraError.value = false
    isLoading.value = false
  }
  testImg.onerror = () => {
    cameraError.value = true
    cameraReady.value = false
    isLoading.value = false
  }
}

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

const onCameraError = () => {
  cameraError.value = true
  cameraReady.value = false
  isLoading.value = false
}

onMounted(() => {
  checkCamera()
  pollingInterval = setInterval(fetchDetection, 5000)
})

onBeforeUnmount(() => {
  clearInterval(pollingInterval)
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
