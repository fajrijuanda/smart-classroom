<script setup lang="ts">
import 'swiper/css';
// import 'swiper/css/pagination'; // 1. Hapus impor CSS untuk pagination
import 'swiper/css/autoplay';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay } from 'swiper/modules'; // 2. Hapus 'Pagination' dari impor modul

const modules = [Autoplay]; // 3. Hapus 'Pagination' dari array modules

// Data dummy (tidak berubah)
const courses = [
    { id: 1, code: 'IF201', name: 'Dasar Pemrograman', lecturer: 'Dr. Budi', thumbnail: 'https://picsum.photos/seed/IF201/400/300' },
    { id: 2, code: 'IF202', name: 'Struktur Data', lecturer: 'Dr. Ani', thumbnail: 'https://picsum.photos/seed/IF202/400/300' },
    { id: 3, code: 'IF301', name: 'Kecerdasan Buatan', lecturer: 'Dr. Charlie', thumbnail: 'https://picsum.photos/seed/IF301/400/300' },
    { id: 4, code: 'UM101', name: 'Pendidikan Pancasila', lecturer: 'Dr. Dewi', thumbnail: 'https://picsum.photos/seed/UM101/400/300' },
    { id: 5, code: 'IF305', name: 'Jaringan Komputer', lecturer: 'Dr. Eka', thumbnail: 'https://picsum.photos/seed/IF305/400/300' },
    { id: 6, code: 'IF401', name: 'Proyek Perangkat Lunak', lecturer: 'Dr. Budi', thumbnail: 'https://picsum.photos/seed/IF401/400/300' },
];
</script>

<template>
    <div>
        <h2 class="text-xl font-bold mb-4">My Courses</h2>
        <swiper :modules="modules" :slides-per-view="4" :space-between="16" :loop="true" :autoplay="{
            delay: 3000,
            disableOnInteraction: false,
        }">
            <swiper-slide v-for="course in courses" :key="course.id">
                <div class="rounded-lg bg-card border overflow-hidden group hover:shadow-lg transition-shadow">
                    <div class="aspect-video overflow-hidden">
                        <img :src="course.thumbnail" :alt="course.name"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                    </div>
                    <div class="p-4">
                        <p class="text-sm text-muted-foreground">{{ course.code }}</p>
                        <h3 class="font-semibold truncate">{{ course.name }}</h3>
                        <p class="text-sm text-muted-foreground mt-2">{{ course.lecturer }}</p>
                    </div>
                </div>
            </swiper-slide>
        </swiper>
    </div>
</template>