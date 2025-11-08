<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref } from 'vue';

interface Resource {
    type: string;
    title: string;
    url: string;
}

interface Skill {
    id: number;
    name: string;
    description: string;
    icon: string;
    category: string;
    xp_rate: number;
    resources?: Resource[];
}

interface UserSkill {
    id: number;
    level: number;
    experience: number;
    experience_to_next_level: number;
    next_level_total_xp: number;
    current_level_xp: number;
    progress_percentage: number;
}

interface TimeEntry {
    id: number;
    started_at: string;
    ended_at: string | null;
    duration_minutes: number | null;
    experience_gained: number | null;
    notes: string | null;
}

const props = defineProps<{
    skill: Skill;
    userSkill: UserSkill;
    recentSessions: TimeEntry[];
    activeSession: TimeEntry | null;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Skills',
        href: '/skills',
    },
    {
        title: props.skill.name,
    },
];

const isTracking = ref(!!props.activeSession);
const elapsedTime = ref(0);
const sessionNotes = ref('');
const isStarting = ref(false);
const isStopping = ref(false);
let timerInterval: ReturnType<typeof setInterval> | null = null;

const formattedTime = computed(() => {
    const hours = Math.floor(elapsedTime.value / 3600);
    const minutes = Math.floor((elapsedTime.value % 3600) / 60);
    const seconds = elapsedTime.value % 60;
    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
});

const estimatedXp = computed(() => {
    const minutes = elapsedTime.value / 60;
    return Math.floor(minutes * props.skill.xp_rate);
});

const formatNumber = (num: number) => {
    return new Intl.NumberFormat().format(num);
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
    });
};

const startTracking = async () => {
    isStarting.value = true;
    try {
        const response = await fetch('/api/time-entries/start', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                skill_id: props.skill.id,
                notes: sessionNotes.value || null,
            }),
        });

        if (response.ok) {
            isTracking.value = true;
            elapsedTime.value = 0;
            startTimer();
            sessionNotes.value = '';
        } else {
            const data = await response.json().catch(() => ({ error: 'Unknown error' }));
            console.error('Start tracking failed:', response.status, data);
            alert(data.error || `Failed to start tracking (Status: ${response.status})`);
        }
    } catch (error) {
        console.error('Error starting tracking:', error);
        alert('Failed to start tracking: ' + (error.message || 'Network error'));
    } finally {
        isStarting.value = false;
    }
};

const stopTracking = async () => {
    isStopping.value = true;
    try {
        const response = await fetch('/api/time-entries/stop', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
        });

        if (response.ok) {
            const data = await response.json();
            isTracking.value = false;
            stopTimer();

            // Reload the page to show updated stats
            router.reload({ only: ['userSkill', 'recentSessions', 'activeSession'] });
        } else {
            const data = await response.json();
            alert(data.error || 'Failed to stop tracking');
        }
    } catch (error) {
        console.error('Error stopping tracking:', error);
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
    if (props.activeSession) {
        const startTime = new Date(props.activeSession.started_at).getTime();
        const now = Date.now();
        elapsedTime.value = Math.floor((now - startTime) / 1000);
        startTimer();
    }
});

onUnmounted(() => {
    stopTimer();
});
</script>

<template>
    <Head :title="skill.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <div class="flex items-start gap-6">
                <div class="text-7xl">{{ skill.icon }}</div>
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        <h1 class="text-4xl font-bold tracking-tight">
                            {{ skill.name }}
                        </h1>
                        <div
                            class="rounded-full bg-primary/10 px-4 py-1.5 text-base font-bold text-primary"
                        >
                            Level {{ userSkill.level }}
                        </div>
                    </div>
                    <p class="text-muted-foreground mt-2 text-lg">
                        {{ skill.description }}
                    </p>
                    <div class="text-muted-foreground mt-2 text-sm">
                        <span class="font-medium">{{ skill.category }}</span>
                        • {{ skill.xp_rate }} XP per minute
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    <div
                        class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
                    >
                        <h2 class="text-xl font-semibold mb-4">
                            Time Tracker
                        </h2>

                        <div class="space-y-6">
                            <div
                                class="rounded-lg bg-secondary/50 p-8 text-center"
                            >
                                <div
                                    class="font-mono text-6xl font-bold tracking-wider"
                                    :class="isTracking ? 'text-green-600 dark:text-green-400' : ''"
                                >
                                    {{ formattedTime }}
                                </div>
                                <div
                                    v-if="isTracking"
                                    class="text-muted-foreground mt-2 text-sm"
                                >
                                    Estimated: +{{ formatNumber(estimatedXp) }}
                                    XP
                                </div>
                            </div>

                            <div v-if="!isTracking" class="space-y-4">
                                <div>
                                    <label
                                        class="text-sm font-medium mb-2 block"
                                    >
                                        Session Notes (Optional)
                                    </label>
                                    <textarea
                                        v-model="sessionNotes"
                                        placeholder="What are you working on?"
                                        class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                        rows="3"
                                    />
                                </div>

                                <button
                                    @click="startTracking"
                                    :disabled="isStarting"
                                    class="w-full rounded-lg bg-green-600 px-6 py-4 text-lg font-semibold text-white transition-colors hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {{ isStarting ? 'Starting...' : 'Start Training' }}
                                </button>
                            </div>

                            <button
                                v-else
                                @click="stopTracking"
                                :disabled="isStopping"
                                class="w-full rounded-lg bg-red-600 px-6 py-4 text-lg font-semibold text-white transition-colors hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {{ isStopping ? 'Stopping...' : 'Stop Training' }}
                            </button>
                        </div>
                    </div>

                    <div
                        class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
                    >
                        <h2 class="text-xl font-semibold mb-4">
                            Recent Sessions
                        </h2>

                        <div
                            v-if="recentSessions.length === 0"
                            class="text-muted-foreground py-8 text-center"
                        >
                            No sessions yet. Start tracking to see your history!
                        </div>

                        <div v-else class="space-y-3">
                            <div
                                v-for="session in recentSessions"
                                :key="session.id"
                                class="flex items-center justify-between rounded-lg border border-sidebar-border/50 p-4"
                            >
                                <div class="flex-1">
                                    <div class="font-medium">
                                        {{ formatDate(session.started_at) }}
                                    </div>
                                    <div
                                        v-if="session.notes"
                                        class="text-muted-foreground text-sm mt-1"
                                    >
                                        {{ session.notes }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold">
                                        {{ session.duration_minutes }} min
                                    </div>
                                    <div class="text-green-600 text-sm dark:text-green-400">
                                        +{{ formatNumber(session.experience_gained || 0) }} XP
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div
                        class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
                    >
                        <h2 class="text-lg font-semibold mb-4">
                            Level Progress
                        </h2>

                        <div class="space-y-4">
                            <div class="text-center">
                                <div class="text-5xl font-bold text-primary">
                                    {{ userSkill.level }}
                                </div>
                                <div class="text-muted-foreground text-sm mt-1">
                                    Current Level
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span class="text-muted-foreground">
                                        XP Progress
                                    </span>
                                    <span class="font-medium">
                                        {{ formatNumber(userSkill.experience - userSkill.current_level_xp) }}
                                        /
                                        {{ formatNumber(userSkill.next_level_total_xp - userSkill.current_level_xp) }}
                                    </span>
                                </div>

                                <div
                                    class="relative h-4 w-full overflow-hidden rounded-full bg-secondary"
                                >
                                    <div
                                        class="h-full rounded-full bg-gradient-to-r from-blue-500 to-purple-500 transition-all duration-500"
                                        :style="`width: ${userSkill.progress_percentage}%`"
                                    />
                                </div>

                                <div class="text-center text-sm font-medium">
                                    {{ userSkill.progress_percentage }}% to
                                    Level {{ userSkill.level + 1 }}
                                </div>
                            </div>

                            <div class="pt-4 border-t border-sidebar-border/50 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">
                                        Total XP
                                    </span>
                                    <span class="font-medium">
                                        {{ formatNumber(userSkill.experience) }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">
                                        XP to Next Level
                                    </span>
                                    <span class="font-medium">
                                        {{ formatNumber(userSkill.experience_to_next_level) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="skill.resources && skill.resources.length > 0"
                        class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
                    >
                        <h2 class="text-lg font-semibold mb-4">
                            Learning Resources
                        </h2>

                        <div class="space-y-3">
                            <a
                                v-for="(resource, index) in skill.resources"
                                :key="index"
                                :href="resource.url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex items-center gap-3 rounded-lg border border-sidebar-border/50 p-4 transition-colors hover:bg-accent hover:border-primary group"
                            >
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary"
                                >
                                    <svg
                                        v-if="resource.type === 'youtube'"
                                        class="h-5 w-5"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                    </svg>
                                    <svg
                                        v-else-if="resource.type === 'website'"
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                    </svg>
                                    <svg
                                        v-else-if="resource.type === 'app'"
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <svg
                                        v-else
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="font-medium group-hover:text-primary transition-colors">
                                        {{ resource.title }}
                                    </div>
                                    <div class="text-muted-foreground text-xs mt-0.5 truncate">
                                        {{ resource.type.charAt(0).toUpperCase() + resource.type.slice(1) }}
                                    </div>
                                </div>

                                <svg
                                    class="h-4 w-4 text-muted-foreground group-hover:text-primary transition-colors"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
