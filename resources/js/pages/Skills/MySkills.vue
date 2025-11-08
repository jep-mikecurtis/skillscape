<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

interface Skill {
    id: number;
    name: string;
    description: string;
    icon: string;
    category: string;
    xp_rate: number;
}

interface UserSkill {
    id: number;
    skill: Skill;
    level: number;
    experience: number;
    experience_to_next_level: number;
    next_level_total_xp: number;
    current_level_xp: number;
    progress_percentage: number;
    recent_sessions: Array<{
        id: number;
        started_at: string;
        ended_at: string | null;
        duration_minutes: number | null;
        experience_gained: number | null;
    }>;
}

const props = defineProps<{
    userSkills: UserSkill[];
    totalLevel: number;
    totalXp: number;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'My Skills',
    },
];

const formatNumber = (num: number) => {
    return new Intl.NumberFormat().format(num);
};
</script>

<template>
    <Head title="My Skills" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">My Skills</h1>
                    <p class="text-muted-foreground mt-1">
                        Track your progress and level up
                    </p>
                </div>
                <Link
                    href="/skills"
                    class="rounded-lg border border-input bg-background px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground"
                >
                    Browse All Skills
                </Link>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div
                    class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
                >
                    <div class="text-muted-foreground text-sm font-medium">
                        Total Level
                    </div>
                    <div class="mt-2 text-3xl font-bold">
                        {{ formatNumber(totalLevel) }}
                    </div>
                </div>
                <div
                    class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
                >
                    <div class="text-muted-foreground text-sm font-medium">
                        Total XP
                    </div>
                    <div class="mt-2 text-3xl font-bold">
                        {{ formatNumber(totalXp) }}
                    </div>
                </div>
                <div
                    class="rounded-xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border"
                >
                    <div class="text-muted-foreground text-sm font-medium">
                        Skills Tracked
                    </div>
                    <div class="mt-2 text-3xl font-bold">
                        {{ userSkills.length }}
                    </div>
                </div>
            </div>

            <div
                v-if="userSkills.length === 0"
                class="rounded-xl border border-sidebar-border/70 bg-card p-12 text-center dark:border-sidebar-border"
            >
                <div class="text-6xl mb-4">🎯</div>
                <h3 class="text-xl font-semibold mb-2">
                    No skills tracked yet
                </h3>
                <p class="text-muted-foreground mb-6">
                    Start tracking your first skill to see your progress here
                </p>
                <Link
                    href="/skills"
                    class="inline-flex rounded-lg bg-primary px-6 py-3 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                >
                    Browse Skills
                </Link>
            </div>

            <div v-else class="grid gap-4 lg:grid-cols-2">
                <Link
                    v-for="userSkill in userSkills"
                    :key="userSkill.id"
                    :href="`/skills/${userSkill.skill.id}`"
                    class="group rounded-xl border border-sidebar-border/70 bg-card p-6 transition-all hover:border-primary hover:shadow-lg dark:border-sidebar-border"
                >
                    <div class="flex items-start gap-4">
                        <div class="text-5xl">{{ userSkill.skill.icon }}</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3
                                    class="font-semibold text-lg text-card-foreground group-hover:text-primary"
                                >
                                    {{ userSkill.skill.name }}
                                </h3>
                                <div
                                    class="rounded-full bg-primary/10 px-3 py-1 text-sm font-bold text-primary"
                                >
                                    Lvl {{ userSkill.level }}
                                </div>
                            </div>

                            <div class="mt-4 space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-muted-foreground">
                                        XP Progress
                                    </span>
                                    <span class="font-medium">
                                        {{ formatNumber(userSkill.experience - userSkill.current_level_xp) }}
                                        /
                                        {{ formatNumber(userSkill.next_level_total_xp - userSkill.current_level_xp) }}
                                    </span>
                                </div>

                                <div class="relative h-3 w-full overflow-hidden rounded-full bg-secondary">
                                    <div
                                        class="h-full rounded-full bg-gradient-to-r from-blue-500 to-purple-500 transition-all duration-500"
                                        :style="`width: ${userSkill.progress_percentage}%`"
                                    />
                                </div>

                                <div class="flex items-center justify-between text-xs text-muted-foreground">
                                    <span>
                                        {{ userSkill.progress_percentage }}% to Level {{ userSkill.level + 1 }}
                                    </span>
                                    <span>
                                        {{ formatNumber(userSkill.experience_to_next_level) }} XP remaining
                                    </span>
                                </div>
                            </div>

                            <div
                                v-if="userSkill.recent_sessions.length > 0"
                                class="mt-4 pt-4 border-t border-sidebar-border/50"
                            >
                                <div class="text-muted-foreground text-xs font-medium mb-2">
                                    Recent Activity
                                </div>
                                <div class="flex gap-1">
                                    <div
                                        v-for="session in userSkill.recent_sessions.slice(0, 5)"
                                        :key="session.id"
                                        class="h-2 flex-1 rounded-full bg-green-500/20"
                                        :title="`${session.duration_minutes} min - ${session.experience_gained} XP`"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
