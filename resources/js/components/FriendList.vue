<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';

const friends = [
    { id: 1, name: 'Andi', avatar: 'https://i.pravatar.cc/150?u=andi', status: 'Online', location: 'A101', course: 'Dasar Pemrograman' },
    { id: 2, name: 'Bunga', avatar: 'https://i.pravatar.cc/150?u=bunga', status: 'Offline' },
    { id: 3, name: 'Citra', avatar: 'https://i.pravatar.cc/150?u=citra', status: 'Online', location: 'B203', course: 'Kalkulus' },
    { id: 4, name: 'Doni', avatar: 'https://i.pravatar.cc/150?u=doni', status: 'Offline' },
];
</script>
<template>
    <div class="bg-card p-4 rounded-lg border h-full">
        <h2 class="text-xl font-bold mb-4">Friends Activity</h2>
        <ul class="space-y-4">
            <li v-for="friend in friends" :key="friend.id" class="flex items-center gap-4">
                <Avatar>
                    <AvatarImage :src="friend.avatar" :alt="friend.name" />
                    <AvatarFallback>{{ friend.name.substring(0, 2) }}</AvatarFallback>
                </Avatar>
                <div class="flex-1">
                    <p class="font-semibold">{{ friend.name }}</p>
                    <div class="text-xs text-muted-foreground flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full" :class="friend.status === 'Online' ? 'bg-green-500' : 'bg-gray-400'"></span>
                        {{ friend.status === 'Online' ? `${friend.location} - ${friend.course}` : 'Offline' }}
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>