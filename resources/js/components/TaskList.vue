<script setup lang="ts">
import { Checkbox } from '@/components/ui/checkbox';
import { ref } from 'vue';

const tasks = ref([
    { id: 1, title: 'Kumpulkan Tugas Algoritma', completed: false },
    { id: 2, title: 'Presentasi Proyek Jaringan', completed: false },
    { id: 3, title: 'Baca materi Bab 5 AI', completed: true },
]);
</script>
<template>
    <div class="bg-card p-4 rounded-lg border h-full">
        <h2 class="text-xl font-bold mb-4">Task List</h2>
        <ul class="space-y-3">
            <li v-for="task in tasks" :key="task.id" class="flex items-center gap-3">
                <Checkbox :id="`task-${task.id}`" :checked="task.completed" @update:checked="task.completed = $event" />
                <label :for="`task-${task.id}`" class="text-sm font-medium" :class="{'line-through text-muted-foreground': task.completed}">
                    {{ task.title }}
                </label>
            </li>
        </ul>
    </div>
</template>