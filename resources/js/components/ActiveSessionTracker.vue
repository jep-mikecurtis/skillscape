<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

interface ActiveSession {
    id: number;
    skill_id: number;
    started_at: string;
    skill: {
        id: number;
        name: string;
        icon: string;
        xp_rate: number;
    };
}

const activeSession = ref<ActiveSession | null>(null);
const elapsedTime = ref(0);
const isExpanded = ref(false);
const isStopping = ref(false);
let timerInterval: ReturnType<typeof setInterval> | null = null;
let pollInterval: ReturnType<typeof setInterval> | null = null;

const formattedTime = computed(() => {
    const hours = Math.floor(elapsedTime.value / 3600);
    const minutes = Math.floor((elapsedTime.value % 3600) / 60);
    const seconds = elapsedTime.value % 60;
    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
});

const estimatedXp = computed(() => {
    if (!activeSession.value) {
        return 0;
    }
    const minutes = elapsedTime.value / 60;
    return Math.floor(minutes * activeSession.value.skill.xp_rate);
});

const formatNumber = (num: number) => {
    return new Intl.NumberFormat().format(num);
};

const fetchActiveSession = async () => {
    try {
        const response = await fetch('/api/time-entries/active', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            credentials: 'same-origin',
        });

        if (response.ok) {
            const data = await response.json();
            if (data.active_entry) {
                activeSession.value = data.active_entry;
                const startTime = new Date(data.active_entry.started_at).getTime();
                const now = Date.now();
                elapsedTime.value = Math.floor((now - startTime) / 1000);

                if (!timerInterval) {
                    startTimer();
                }
            } else {
                activeSession.value = null;
                stopTimer();
            }
        }
    } catch (error) {
        console.error('Error fetching active session:', error);
    }
};

const stopSession = async () => {
    isStopping.value = true;
    try {
        const response = await fetch('/api/time-entries/stop', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            credentials: 'same-origin',
        });

        if (response.ok) {
            activeSession.value = null;
            stopTimer();
            router.reload();
        } else {
            const data = await response.json();
            alert(data.error || 'Failed to stop tracking');
        }
    } catch (error) {
        console.error('Error stopping session:', error);
        alert('Failed to stop tracking');
    } finally {
        isStopping.value = false;
    }
};

const startTimer = () => {
    if (timerInterval) {
        clearInterval(timerInterval);
    }
    timerInterval = setInterval(() => {
        elapsedTime.value++;
    }, 1000);
};

const stopTimer = () => {
    if (timerInterval) {
        clearInterval(timerInterval);
        timerInterval = null;
    }
};

onMounted(() => {
    fetchActiveSession();
    pollInterval = setInterval(fetchActiveSession, 30000);
});

onUnmounted(() => {
    stopTimer();
    if (pollInterval) {
        clearInterval(pollInterval);
    }
});
</script>

<template>
    <div
        v-if="activeSession"
        class="fixed bottom-6 right-6 z-50 transition-all"
    >
        <div
            class="overflow-hidden rounded-xl border-2 border-green-500/50 bg-card shadow-2xl"
            :class="isExpanded ? 'w-80' : 'w-64'"
        >
            <div
                class="flex cursor-pointer items-center gap-3 bg-gradient-to-r from-green-500/10 to-blue-500/10 p-4"
                @click="isExpanded = !isExpanded"
            >
                <div class="text-2xl">{{ activeSession.skill.icon }}</div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs font-medium text-green-600 dark:text-green-400">
                        TRAINING
                    </div>
                    <div class="font-semibold truncate">
                        {{ activeSession.skill.name }}
                    </div>
                </div>
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500/20 text-green-600 dark:text-green-400"
                >
                    <svg
                        class="h-4 w-4 animate-pulse"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <circle cx="10" cy="10" r="6" />
                    </svg>
                </div>
            </div>

            <div class="p-4 space-y-3">
                <div class="text-center">
                    <div
                        class="font-mono text-3xl font-bold tracking-wider text-green-600 dark:text-green-400"
                    >
                        {{ formattedTime }}
                    </div>
                    <div class="text-muted-foreground text-xs mt-1">
                        +{{ formatNumber(estimatedXp) }} XP
                    </div>
                </div>

                <div v-if="isExpanded" class="space-y-2">
                    <Link
                        :href="`/skills/${activeSession.skill_id}`"
                        class="block w-full rounded-lg border border-input bg-background px-4 py-2 text-center text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground"
                    >
                        View Skill
                    </Link>

                    <button
                        @click.stop="stopSession"
                        :disabled="isStopping"
                        class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ isStopping ? 'Stopping...' : 'Stop Training' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
