<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Skill {
    id: number;
    name: string;
    description: string;
    icon: string;
    category: string;
    xp_rate: number;
    is_active: boolean;
}

const props = defineProps<{
    skills: Skill[];
    userSkillIds: number[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'All Skills',
    },
];

const groupedSkills = computed(() => {
    const groups: Record<string, Skill[]> = {};
    props.skills.forEach((skill) => {
        if (!groups[skill.category]) {
            groups[skill.category] = [];
        }
        groups[skill.category].push(skill);
    });
    return groups;
});

const isTracked = (skillId: number) => props.userSkillIds.includes(skillId);
</script>

<template>
    <Head title="All Skills" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">
                        All Skills
                    </h1>
                    <p class="text-muted-foreground mt-1">
                        Choose skills to track and level up in real life
                    </p>
                </div>
                <Link
                    href="/skills/my-skills"
                    class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                >
                    My Skills
                </Link>
            </div>

            <div
                v-for="(skillsList, category) in groupedSkills"
                :key="category"
                class="space-y-4"
            >
                <div>
                    <h2 class="text-xl font-semibold">{{ category }}</h2>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <Link
                        v-for="skill in skillsList"
                        :key="skill.id"
                        :href="`/skills/${skill.id}`"
                        class="group relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-card p-6 transition-all hover:border-primary hover:shadow-lg dark:border-sidebar-border"
                    >
                        <div class="flex items-start gap-4">
                            <div class="text-4xl">{{ skill.icon }}</div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <h3
                                        class="font-semibold text-card-foreground group-hover:text-primary"
                                    >
                                        {{ skill.name }}
                                    </h3>
                                    <span
                                        v-if="isTracked(skill.id)"
                                        class="rounded-full bg-green-500/10 px-2 py-0.5 text-xs font-medium text-green-600 dark:text-green-400"
                                    >
                                        Tracking
                                    </span>
                                </div>
                                <p class="text-muted-foreground mt-1 text-sm">
                                    {{ skill.description }}
                                </p>
                                <div class="text-muted-foreground mt-2 text-xs">
                                    {{ skill.xp_rate }} XP/min
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
