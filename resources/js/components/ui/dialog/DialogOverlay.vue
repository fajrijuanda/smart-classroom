<script setup lang="ts">
import { DialogOverlay, type DialogOverlayProps } from 'radix-vue'
import { cn } from '@/lib/utils'
const props = defineProps<DialogOverlayProps & { class?: string }>()
</script>
<template>
  <DialogOverlay
    :class="cn('fixed inset-0 z-50 bg-black/80 data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0', props.class)"
  />
</template>