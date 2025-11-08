<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { BookOpen, Edit, Plus, Trash2, Trophy } from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface Skill {
    id: number;
    name: string;
    description: string;
    icon: string;
}

interface UserSkill {
    id: number;
    level: number;
    experience: number;
}

interface Flashcard {
    id: number;
    question: string;
    answer: string;
    times_studied: number;
    times_correct: number;
    accuracy: number;
    last_studied_at: string | null;
    created_at: string;
}

const props = defineProps<{
    skill: Skill;
    userSkill: UserSkill;
    flashcards: Flashcard[];
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
        href: `/skills/${props.skill.id}`,
    },
    {
        title: 'Flashcards',
        href: `/skills/${props.skill.id}/flashcards`,
    },
];

const showCreateModal = ref(false);
const editingFlashcard = ref<Flashcard | null>(null);
const showDeleteConfirm = ref(false);
const flashcardToDelete = ref<Flashcard | null>(null);

const form = useForm({
    question: '',
    answer: '',
});

const isEditing = computed(() => editingFlashcard.value !== null);

const openCreateModal = () => {
    form.reset();
    editingFlashcard.value = null;
    showCreateModal.value = true;
};

const openEditModal = (flashcard: Flashcard) => {
    form.question = flashcard.question;
    form.answer = flashcard.answer;
    editingFlashcard.value = flashcard;
    showCreateModal.value = true;
};

const closeModal = () => {
    showCreateModal.value = false;
    editingFlashcard.value = null;
    form.reset();
};

const submitForm = () => {
    if (isEditing.value && editingFlashcard.value) {
        form.patch(`/skills/${props.skill.id}/flashcards/${editingFlashcard.value.id}`, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(`/skills/${props.skill.id}/flashcards`, {
            onSuccess: () => closeModal(),
        });
    }
};

const confirmDelete = (flashcard: Flashcard) => {
    flashcardToDelete.value = flashcard;
    showDeleteConfirm.value = true;
};

const deleteFlashcard = () => {
    if (flashcardToDelete.value) {
        router.delete(`/skills/${props.skill.id}/flashcards/${flashcardToDelete.value.id}`, {
            onSuccess: () => {
                showDeleteConfirm.value = false;
                flashcardToDelete.value = null;
            },
        });
    }
};

const startStudyMode = () => {
    router.visit(`/skills/${props.skill.id}/flashcards/study`);
};
</script>

<template>
    <Head :title="`${skill.name} - Flashcards`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <!-- Header -->
            <div class="flex items-start justify-between gap-6">
                <div class="flex items-start gap-6">
                    <div class="text-7xl">{{ skill.icon }}</div>
                    <div class="flex-1">
                        <h1 class="text-4xl font-bold tracking-tight rs-heading">
                            {{ skill.name }} Flashcards
                        </h1>
                        <p class="text-muted-foreground mt-2 text-lg">
                            Create and study flashcards to master this skill
                        </p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button
                        v-if="flashcards.length > 0"
                        @click="startStudyMode"
                        class="rs-button bg-accent border-accent text-accent-foreground px-6 py-3"
                    >
                        <BookOpen class="inline-block size-5 mr-2" />
                        Study Mode
                    </button>
                    <button
                        @click="openCreateModal"
                        class="rs-button bg-primary border-primary text-primary-foreground px-6 py-3"
                    >
                        <Plus class="inline-block size-5 mr-2" />
                        New Flashcard
                    </button>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rs-panel">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-primary/10 p-3">
                            <BookOpen class="size-6 text-primary" />
                        </div>
                        <div>
                            <div class="text-2xl font-bold">{{ flashcards.length }}</div>
                            <div class="text-muted-foreground text-sm">Total Cards</div>
                        </div>
                    </div>
                </div>

                <div class="rs-panel">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-green-500/10 p-3">
                            <Trophy class="size-6 text-green-600 dark:text-green-400" />
                        </div>
                        <div>
                            <div class="text-2xl font-bold">
                                {{
                                    flashcards.length > 0
                                        ? Math.round(
                                              flashcards.reduce((sum, card) => sum + card.accuracy, 0) /
                                                  flashcards.length,
                                          )
                                        : 0
                                }}%
                            </div>
                            <div class="text-muted-foreground text-sm">Average Accuracy</div>
                        </div>
                    </div>
                </div>

                <div class="rs-panel">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-blue-500/10 p-3">
                            <BookOpen class="size-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <div class="text-2xl font-bold">
                                {{
                                    flashcards.reduce((sum, card) => sum + card.times_studied, 0)
                                }}
                            </div>
                            <div class="text-muted-foreground text-sm">Times Studied</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flashcards Grid -->
            <div v-if="flashcards.length === 0" class="rs-panel text-center py-16">
                <BookOpen class="size-16 mx-auto text-muted-foreground mb-4 opacity-50" />
                <h3 class="text-xl font-semibold mb-2">No flashcards yet</h3>
                <p class="text-muted-foreground mb-6">
                    Create your first flashcard to start studying!
                </p>
                <button
                    @click="openCreateModal"
                    class="rs-button bg-primary border-primary text-primary-foreground px-6 py-3"
                >
                    <Plus class="inline-block size-5 mr-2" />
                    Create Flashcard
                </button>
            </div>

            <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="flashcard in flashcards"
                    :key="flashcard.id"
                    class="rs-card p-6 relative group"
                >
                    <!-- Actions -->
                    <div class="absolute top-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button
                            @click="openEditModal(flashcard)"
                            class="p-2 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary transition-colors"
                            title="Edit"
                        >
                            <Edit class="size-4" />
                        </button>
                        <button
                            @click="confirmDelete(flashcard)"
                            class="p-2 rounded-lg bg-destructive/10 hover:bg-destructive/20 text-destructive transition-colors"
                            title="Delete"
                        >
                            <Trash2 class="size-4" />
                        </button>
                    </div>

                    <!-- Question -->
                    <div class="mb-4 pr-20">
                        <div class="text-xs text-muted-foreground mb-1 font-medium">
                            QUESTION
                        </div>
                        <div class="font-semibold line-clamp-3">
                            {{ flashcard.question }}
                        </div>
                    </div>

                    <!-- Answer Preview -->
                    <div class="mb-4">
                        <div class="text-xs text-muted-foreground mb-1 font-medium">
                            ANSWER
                        </div>
                        <div class="text-sm text-muted-foreground line-clamp-2">
                            {{ flashcard.answer }}
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="flex items-center justify-between pt-4 border-t border-sidebar-border/50">
                        <div class="text-xs">
                            <span class="text-muted-foreground">Studied:</span>
                            <span class="font-semibold ml-1">{{ flashcard.times_studied }}</span>
                        </div>
                        <div
                            class="text-xs font-semibold px-2 py-1 rounded"
                            :class="{
                                'bg-green-500/10 text-green-600 dark:text-green-400':
                                    flashcard.accuracy >= 70,
                                'bg-yellow-500/10 text-yellow-600 dark:text-yellow-400':
                                    flashcard.accuracy >= 40 && flashcard.accuracy < 70,
                                'bg-red-500/10 text-red-600 dark:text-red-400':
                                    flashcard.accuracy < 40,
                                'bg-muted text-muted-foreground': flashcard.times_studied === 0,
                            }"
                        >
                            {{ flashcard.accuracy }}% accuracy
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div
            v-if="showCreateModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="background-color: rgba(0, 0, 0, 0.75);"
            @click.self="closeModal"
        >
            <div class="relative max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal with enhanced medieval styling -->
                <div class="rounded-xl border-4 border-primary/30 bg-card shadow-2xl" style="box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5), inset 0 2px 0 rgba(255, 255, 255, 0.1), inset 0 -2px 0 rgba(0, 0, 0, 0.2);">
                    <!-- Header with decorative border -->
                    <div class="border-b-4 border-primary/20 bg-gradient-to-r from-primary/5 via-primary/10 to-primary/5 px-8 py-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="text-4xl">📚</div>
                                <h2 class="text-3xl font-bold rs-heading">
                                    {{ isEditing ? 'Edit Flashcard' : 'New Flashcard' }}
                                </h2>
                            </div>
                            <button
                                @click="closeModal"
                                class="rs-button bg-secondary/50 border-secondary text-secondary-foreground px-3 py-2 hover:bg-secondary/70"
                                type="button"
                            >
                                <span class="text-xl">×</span>
                            </button>
                        </div>
                    </div>

                    <!-- Form content -->
                    <form @submit.prevent="submitForm" class="px-8 py-6 space-y-6">
                        <div>
                            <label class="text-sm font-bold mb-3 block rs-gold-text uppercase tracking-wide">
                                <span class="text-base">⚔️</span> Question <span class="text-destructive">*</span>
                            </label>
                            <textarea
                                v-model="form.question"
                                placeholder="What do you want to remember?"
                                class="rs-input w-full px-4 py-3 text-base transition-all focus:border-primary"
                                style="min-height: 100px; resize: vertical;"
                                required
                                autofocus
                            />
                            <div v-if="form.errors.question" class="text-destructive text-sm mt-2 font-medium">
                                ⚠️ {{ form.errors.question }}
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-bold mb-3 block rs-gold-text uppercase tracking-wide">
                                <span class="text-base">📖</span> Answer <span class="text-destructive">*</span>
                            </label>
                            <textarea
                                v-model="form.answer"
                                placeholder="What's the answer?"
                                class="rs-input w-full px-4 py-3 text-base transition-all focus:border-primary"
                                style="min-height: 150px; resize: vertical;"
                                required
                            />
                            <div v-if="form.errors.answer" class="text-destructive text-sm mt-2 font-medium">
                                ⚠️ {{ form.errors.answer }}
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div class="flex gap-4 justify-end pt-4 border-t-2 border-sidebar-border/30">
                            <button
                                type="button"
                                @click="closeModal"
                                class="rs-button bg-secondary border-secondary text-secondary-foreground px-8 py-3 text-base"
                                :disabled="form.processing"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="rs-button rs-button-glow bg-primary border-primary text-primary-foreground px-8 py-3 text-base font-bold"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing">⏳ Saving...</span>
                                <span v-else>
                                    <span v-if="isEditing">💾 Update Flashcard</span>
                                    <span v-else>✨ Create Flashcard</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div
            v-if="showDeleteConfirm"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="background-color: rgba(0, 0, 0, 0.75);"
            @click.self="showDeleteConfirm = false"
        >
            <div class="relative max-w-md w-full">
                <div class="rounded-xl border-4 border-destructive/30 bg-card shadow-2xl" style="box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5), inset 0 2px 0 rgba(255, 255, 255, 0.1), inset 0 -2px 0 rgba(0, 0, 0, 0.2);">
                    <!-- Header -->
                    <div class="border-b-4 border-destructive/20 bg-gradient-to-r from-destructive/5 via-destructive/10 to-destructive/5 px-8 py-6">
                        <div class="flex items-center gap-3">
                            <div class="text-4xl">⚠️</div>
                            <h2 class="text-2xl font-bold rs-heading text-destructive">Delete Flashcard?</h2>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="px-8 py-6">
                        <p class="text-base mb-6 leading-relaxed">
                            Are you sure you want to delete this flashcard? <strong>This action cannot be undone.</strong>
                        </p>

                        <!-- Buttons -->
                        <div class="flex gap-4 justify-end">
                            <button
                                @click="showDeleteConfirm = false"
                                class="rs-button bg-secondary border-secondary text-secondary-foreground px-8 py-3 text-base"
                            >
                                Cancel
                            </button>
                            <button
                                @click="deleteFlashcard"
                                class="rs-button bg-destructive border-destructive text-destructive-foreground px-8 py-3 text-base font-bold"
                            >
                                🗑️ Delete Forever
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
