<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

interface Skill {
    id: number;
    name: string;
    icon: string;
    xp_rate: number;
}

interface TopSkill {
    skill: Skill;
    level: number;
    experience: number;
}

interface RecentSession {
    id: number;
    skill: Skill;
    started_at: string;
    ended_at: string | null;
    duration_minutes: number | null;
    experience_gained: number | null;
}

const props = defineProps<{
    stats: {
        totalLevel: number;
        totalXp: number;
        skillsTracked: number;
    };
    topSkills: TopSkill[];
    recentSessions: RecentSession[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const formatNumber = (num: number) => {
    return new Intl.NumberFormat().format(num);
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Dashboard</h1>
                <p class="text-muted-foreground mt-1">
                    Welcome back! Here's your skill progress overview
                </p>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div
                    class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
                >
                    <div class="text-muted-foreground text-sm font-medium">
                        Total Level
                    </div>
                    <div class="mt-2 text-4xl font-bold">
                        {{ formatNumber(stats.totalLevel) }}
                    </div>
                    <p class="text-muted-foreground text-xs mt-2">
                        Combined levels across all skills
                    </p>
                </div>
                <div
                    class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
                >
                    <div class="text-muted-foreground text-sm font-medium">
                        Total Experience
                    </div>
                    <div class="mt-2 text-4xl font-bold">
                        {{ formatNumber(stats.totalXp) }}
                    </div>
                    <p class="text-muted-foreground text-xs mt-2">
                        XP earned from all activities
                    </p>
                </div>
                <div
                    class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
                >
                    <div class="text-muted-foreground text-sm font-medium">
                        Skills Tracked
                    </div>
                    <div class="mt-2 text-4xl font-bold">
                        {{ stats.skillsTracked }}
                    </div>
                    <p class="text-muted-foreground text-xs mt-2">
                        Active skills in progress
                    </p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div
                    class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
                >
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold">Top Skills</h2>
                        <Link
                            href="/skills/my-skills"
                            class="text-sm text-primary hover:underline"
                        >
                            View All
                        </Link>
                    </div>

                    <div
                        v-if="topSkills.length === 0"
                        class="text-muted-foreground py-8 text-center"
                    >
                        <div class="text-4xl mb-2">🎯</div>
                        <p class="text-sm">No skills yet!</p>
                        <Link
                            href="/skills"
                            class="text-primary hover:underline text-sm mt-2 inline-block"
                        >
                            Browse skills to get started
                        </Link>
                    </div>

                    <div v-else class="space-y-3">
                        <Link
                            v-for="topSkill in topSkills"
                            :key="topSkill.skill.id"
                            :href="`/skills/${topSkill.skill.id}`"
                            class="flex items-center justify-between rounded-lg border border-sidebar-border/50 p-4 transition-colors hover:bg-accent"
                        >
                            <div class="flex items-center gap-3">
                                <div class="text-3xl">
                                    {{ topSkill.skill.icon }}
                                </div>
                                <div>
                                    <div class="font-semibold">
                                        {{ topSkill.skill.name }}
                                    </div>
                                    <div class="text-muted-foreground text-sm">
                                        {{ formatNumber(topSkill.experience) }} XP
                                    </div>
                                </div>
                            </div>
                            <div
                                class="rounded-full bg-primary/10 px-3 py-1 text-sm font-bold text-primary"
                            >
                                Lvl {{ topSkill.level }}
                            </div>
                        </Link>
                    </div>
                </div>

                <div
                    class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
                >
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold">Recent Sessions</h2>
                    </div>

                    <div
                        v-if="recentSessions.length === 0"
                        class="text-muted-foreground py-8 text-center"
                    >
                        <div class="text-4xl mb-2">⏱️</div>
                        <p class="text-sm">No training sessions yet!</p>
                    </div>

                    <div v-else class="space-y-3">
                        <div
                            v-for="session in recentSessions"
                            :key="session.id"
                            class="flex items-center justify-between rounded-lg border border-sidebar-border/50 p-4"
                        >
                            <div class="flex items-center gap-3">
                                <div class="text-2xl">
                                    {{ session.skill.icon }}
                                </div>
                                <div>
                                    <div class="font-medium">
                                        {{ session.skill.name }}
                                    </div>
                                    <div class="text-muted-foreground text-xs">
                                        {{ formatDate(session.started_at) }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-sm">
                                    {{ session.duration_minutes }} min
                                </div>
                                <div class="text-green-600 text-xs dark:text-green-400">
                                    +{{ formatNumber(session.experience_gained || 0) }} XP
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <Link
                    href="/skills"
                    class="flex-1 rounded-xl border-2 border-dashed border-sidebar-border/70 bg-card p-8 text-center transition-colors hover:border-primary hover:bg-accent dark:border-sidebar-border"
                >
                    <div class="text-4xl mb-2">🎯</div>
                    <h3 class="font-semibold mb-1">Browse All Skills</h3>
                    <p class="text-muted-foreground text-sm">
                        Discover new skills to track
                    </p>
                </Link>

                <Link
                    href="/skills/my-skills"
                    class="flex-1 rounded-xl border-2 border-dashed border-sidebar-border/70 bg-card p-8 text-center transition-colors hover:border-primary hover:bg-accent dark:border-sidebar-border"
                >
                    <div class="text-4xl mb-2">📊</div>
                    <h3 class="font-semibold mb-1">My Progress</h3>
                    <p class="text-muted-foreground text-sm">
                        View detailed skill progress
                    </p>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
