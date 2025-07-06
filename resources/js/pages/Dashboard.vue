<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import CoursesSwiper from '@/components/CoursesSwiper.vue';
import CalendarTabs from '@/components/CalendarTabs.vue';
import FriendList from '@/components/FriendList.vue';
import TaskList from '@/components/TaskList.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-8 p-6 overflow-auto">
            <CoursesSwiper />

            <div class="grid flex-1 grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2">
                    <CalendarTabs />
                </div>

                <div class="flex flex-col gap-8">
                    <FriendList />
                    <TaskList />
                </div>

            </div>
        </div>
    </AppLayout>
</template>