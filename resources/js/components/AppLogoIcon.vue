<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 100 100"
        :class="className"
        v-bind="$attrs"
    >
        <!-- Medieval Shield -->
        <path
            fill="currentColor"
            d="M50 5 C30 5, 15 10, 10 15 L10 45 C10 65, 25 85, 50 95 C75 85, 90 65, 90 45 L90 15 C85 10, 70 5, 50 5 Z"
            opacity="0.9"
        />

        <!-- Shield Border/Highlight -->
        <path
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            d="M50 8 C31 8, 17 12, 13 17 L13 45 C13 63, 27 81, 50 90 C73 81, 87 63, 87 45 L87 17 C83 12, 69 8, 50 8 Z"
            opacity="0.4"
        />

        <!-- Sword -->
        <g opacity="0.95">
            <!-- Blade -->
            <path
                fill="currentColor"
                d="M50 25 L47 55 L53 55 Z"
                opacity="0.3"
            />
            <!-- Cross guard -->
            <rect
                x="42"
                y="54"
                width="16"
                height="3"
                fill="currentColor"
                opacity="0.3"
            />
            <!-- Grip -->
            <rect
                x="48"
                y="57"
                width="4"
                height="10"
                rx="1"
                fill="currentColor"
                opacity="0.3"
            />
            <!-- Pommel -->
            <circle
                cx="50"
                cy="68"
                r="3"
                fill="currentColor"
                opacity="0.3"
            />
        </g>

        <!-- Scroll/Banner Behind -->
        <path
            fill="currentColor"
            d="M25 72 L25 78 C25 80, 27 82, 30 82 L70 82 C73 82, 75 80, 75 78 L75 72 Z"
            opacity="0.25"
        />

        <!-- Stars/Level indicators on sides -->
        <circle cx="28" cy="35" r="2.5" fill="currentColor" opacity="0.3" />
        <circle cx="72" cy="35" r="2.5" fill="currentColor" opacity="0.3" />
        <circle cx="24" cy="48" r="2" fill="currentColor" opacity="0.25" />
        <circle cx="76" cy="48" r="2" fill="currentColor" opacity="0.25" />
    </svg>
</template>
