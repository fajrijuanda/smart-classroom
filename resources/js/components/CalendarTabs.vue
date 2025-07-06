<script setup lang="ts">
import { ref, nextTick } from 'vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import FullCalendar from '@fullcalendar/vue3';
import type { CalendarApi, EventClickArg } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import { Link } from '@inertiajs/vue3';

// State untuk modal
const isModalOpen = ref(false);
const selectedEvent = ref<any>(null);

const handleEventClick = (clickInfo: EventClickArg) => {
    selectedEvent.value = {
        title: clickInfo.event.title,
        start: clickInfo.event.start?.toLocaleString() ?? 'N/A',
        end: clickInfo.event.end?.toLocaleString() ?? 'N/A',
        lecturer: clickInfo.event.extendedProps.lecturer,
        room: clickInfo.event.extendedProps.room,
        courseId: clickInfo.event.extendedProps.courseId,
    };
    isModalOpen.value = true;
};

// State untuk kalender
const scheduleCalendarRef = ref<{ getApi: () => CalendarApi } | null>(null);
const academicCalendarRef = ref<{ getApi: () => CalendarApi } | null>(null);

const calendarOptions = (events: any) => ({
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    events: events,
    eventClick: handleEventClick,
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek'
    },
    height: '100%',
});

const scheduleEvents = [{ title: 'Dasar Pemrograman', start: '2025-07-07T10:00:00', end: '2025-07-07T12:00:00', extendedProps: { lecturer: 'Dr. Budi', room: 'A101', courseId: 1 } }, { title: 'Struktur Data', start: '2025-07-08T13:00:00', end: '2025-07-08T15:00:00', extendedProps: { lecturer: 'Dr. Ani', room: 'B203', courseId: 2 } },];
const academicEvents = [{ title: 'Libur Idul Adha', start: '2025-07-10', end: '2025-07-11', allDay: true, display: 'background', color: '#ff9f89', extendedProps: {} }, { title: 'Ujian Tengah Semester', start: '2025-07-21', end: '2025-07-25', allDay: true, display: 'background', color: '#89caff', extendedProps: {} },];

// Fungsi ini akan dipanggil setiap kali tab berubah
const handleTabChange = async (tabValue: string | number) => {
    // Tunggu hingga DOM selesai diperbarui dan tab baru terlihat
    await nextTick();

    // Periksa tab mana yang aktif dan panggil .updateSize()
    if (tabValue === 'schedule' && scheduleCalendarRef.value) {
        // Perintah ini adalah kunci utamanya: update ukuran kalender!
        scheduleCalendarRef.value.getApi().updateSize();
    } else if (tabValue === 'academic' && academicCalendarRef.value) {
        // Lakukan hal yang sama untuk kalender akademik
        academicCalendarRef.value.getApi().updateSize();
    }
}
</script>

<template>
    <Dialog v-model:open="isModalOpen">
        <div class="bg-card p-4 rounded-lg border h-full">
            <Tabs default-value="schedule" @update:model-value="handleTabChange" class="flex flex-col h-full">
                <TabsList class="mb-4 shrink-0">
                    <TabsTrigger value="schedule">My Schedule</TabsTrigger>
                    <TabsTrigger value="academic">Academic Calendar</TabsTrigger>
                </TabsList>

                <TabsContent value="schedule" class="flex-1 overflow-hidden" force-mount>
                    <FullCalendar ref="scheduleCalendarRef" :options="calendarOptions(scheduleEvents)" />
                </TabsContent>
                <TabsContent value="academic" class="flex-1 overflow-hidden" force-mount>
                    <FullCalendar ref="academicCalendarRef" :options="calendarOptions(academicEvents)" />
                </TabsContent>
            </Tabs>
        </div>

        <DialogContent v-if="selectedEvent" class="flex flex-col max-h-[90dvh]">
            <DialogHeader class="flex-shrink-0">
                <DialogTitle>{{ selectedEvent.title }}</DialogTitle>
                <DialogDescription>Detail jadwal mata kuliah.</DialogDescription>
            </DialogHeader>
            <div class="flex-1 overflow-y-auto -mx-6 px-6">
                <div class="grid gap-4 py-4">
                    <div v-if="selectedEvent.lecturer" class="grid grid-cols-4 items-center gap-4"><span
                            class="text-right font-semibold">Dosen</span><span class="col-span-3">{{
                            selectedEvent.lecturer }}</span></div>
                    <div v-if="selectedEvent.room" class="grid grid-cols-4 items-center gap-4"><span
                            class="text-right font-semibold">Ruangan</span><span class="col-span-3">{{
                            selectedEvent.room }}</span></div>
                    <div class="grid grid-cols-4 items-center gap-4"><span
                            class="text-right font-semibold">Waktu</span><span class="col-span-3">{{ selectedEvent.start
                            }}</span></div>
                </div>
            </div>
            <DialogFooter class="mt-auto flex-shrink-0">
                <Link v-if="selectedEvent.courseId" :href="route('courses.show', { course: selectedEvent.courseId })">
                <Button>Lihat Detail Course</Button></Link>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>