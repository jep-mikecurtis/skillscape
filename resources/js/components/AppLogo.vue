<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <div
        class="flex aspect-square size-10 items-center justify-center rounded-sm bg-gradient-to-br from-primary to-accent border-2 border-primary/50 shadow-lg"
    >
        <AppLogoIcon class="size-7 text-primary-foreground drop-shadow-md" />
    </div>
    <div class="ml-2 grid flex-1 text-left">
        <span class="truncate leading-tight font-bold text-base text-sidebar-foreground" style="font-family: 'Cinzel', serif; letter-spacing: 0.05em;">
            SKILLSCAPE
        </span>
        <span class="text-xs text-sidebar-foreground/60 font-medium tracking-wide">
            Master Your Journey
        </span>
    </div>
</template>
