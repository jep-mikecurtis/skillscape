<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeft, Check, RotateCcw, X } from 'lucide-vue-next';
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
    accuracy: number;
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
    {
        title: 'Study',
    },
];

const currentIndex = ref(0);
const showAnswer = ref(false);
const correctCount = ref(0);
const incorrectCount = ref(0);
const isFinished = ref(false);

const currentCard = computed(() => props.flashcards[currentIndex.value]);
const progress = computed(() =>
    Math.round(((correctCount.value + incorrectCount.value) / props.flashcards.length) * 100)
);
const totalAnswered = computed(() => correctCount.value + incorrectCount.value);
const accuracy = computed(() =>
    totalAnswered.value > 0
        ? Math.round((correctCount.value / totalAnswered.value) * 100)
        : 0
);

const flipCard = () => {
    showAnswer.value = !showAnswer.value;
};

const markCorrect = async () => {
    await recordAnswer(true);
    correctCount.value++;
    nextCard();
};

const markIncorrect = async () => {
    await recordAnswer(false);
    incorrectCount.value++;
    nextCard();
};

const recordAnswer = async (correct: boolean) => {
    await fetch(`/skills/${props.skill.id}/flashcards/${currentCard.value.id}/answer`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json',
        },
        credentials: 'same-origin',
        body: JSON.stringify({ correct }),
    });
};

const nextCard = () => {
    showAnswer.value = false;
    if (currentIndex.value < props.flashcards.length - 1) {
        currentIndex.value++;
    } else {
        isFinished.value = true;
    }
};

const restart = () => {
    currentIndex.value = 0;
    showAnswer.value = false;
    correctCount.value = 0;
    incorrectCount.value = 0;
    isFinished.value = false;
    router.reload({ only: ['flashcards'] });
};

const goBack = () => {
    router.visit(`/skills/${props.skill.id}/flashcards`);
};
</script>

<template>
    <Head :title="`${skill.name} - Study`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <button
                    @click="goBack"
                    class="rs-button bg-secondary border-secondary text-secondary-foreground px-4 py-2"
                >
                    <ArrowLeft class="inline-block size-5 mr-2" />
                    Back to Flashcards
                </button>

                <div class="text-center">
                    <div class="text-2xl font-bold rs-heading">{{ skill.icon }} {{ skill.name }}</div>
                    <div class="text-muted-foreground text-sm">Study Mode</div>
                </div>

                <div class="w-32"></div>
            </div>

            <!-- Progress Bar -->
            <div v-if="!isFinished" class="rs-panel">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium">Progress</span>
                    <span class="text-sm font-medium">{{ totalAnswered }} / {{ flashcards.length }}</span>
                </div>
                <div class="relative h-4 w-full overflow-hidden rounded-full bg-secondary">
                    <div
                        class="h-full rounded-full bg-gradient-to-r from-blue-500 to-purple-500 transition-all duration-500"
                        :style="`width: ${progress}%`"
                    />
                </div>
            </div>

            <!-- Study Card -->
            <div v-if="!isFinished && currentCard" class="flex-1 flex items-center justify-center">
                <div class="max-w-3xl w-full">
                    <!-- Card Counter -->
                    <div class="text-center text-muted-foreground text-sm mb-4">
                        Card {{ currentIndex + 1 }} of {{ flashcards.length }}
                    </div>

                    <!-- Flashcard -->
                    <div
                        class="rs-panel min-h-[400px] flex flex-col cursor-pointer select-none relative"
                        @click="flipCard"
                    >
                        <div class="absolute top-4 right-4 text-xs text-muted-foreground font-medium uppercase">
                            {{ showAnswer ? 'Answer' : 'Question' }}
                        </div>

                        <div class="flex-1 flex items-center justify-center p-12">
                            <div class="text-center">
                                <p class="text-2xl leading-relaxed">
                                    {{ showAnswer ? currentCard.answer : currentCard.question }}
                                </p>
                            </div>
                        </div>

                        <div class="text-center text-sm text-muted-foreground pb-6">
                            {{ showAnswer ? 'Click to see question' : 'Click to reveal answer' }}
                        </div>
                    </div>

                    <!-- Answer Buttons -->
                    <div v-if="showAnswer" class="grid grid-cols-2 gap-4 mt-6">
                        <button
                            @click.stop="markIncorrect"
                            class="rs-button bg-red-600 border-red-600 text-white px-8 py-6 text-lg"
                        >
                            <X class="inline-block size-6 mr-2" />
                            Incorrect
                        </button>
                        <button
                            @click.stop="markCorrect"
                            class="rs-button bg-green-600 border-green-600 text-white px-8 py-6 text-lg"
                        >
                            <Check class="inline-block size-6 mr-2" />
                            Correct
                        </button>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-4 mt-6">
                        <div class="rs-panel text-center">
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                                {{ correctCount }}
                            </div>
                            <div class="text-sm text-muted-foreground">Correct</div>
                        </div>
                        <div class="rs-panel text-center">
                            <div class="text-3xl font-bold text-red-600 dark:text-red-400">
                                {{ incorrectCount }}
                            </div>
                            <div class="text-sm text-muted-foreground">Incorrect</div>
                        </div>
                        <div class="rs-panel text-center">
                            <div class="text-3xl font-bold text-primary">
                                {{ accuracy }}%
                            </div>
                            <div class="text-sm text-muted-foreground">Accuracy</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completion Screen -->
            <div v-else-if="isFinished" class="flex-1 flex items-center justify-center">
                <div class="max-w-2xl w-full text-center">
                    <div class="text-7xl mb-6">🎉</div>
                    <h2 class="text-4xl font-bold mb-4 rs-heading">Study Session Complete!</h2>
                    <p class="text-muted-foreground text-lg mb-8">
                        Great job! You've reviewed all {{ flashcards.length }} flashcards.
                    </p>

                    <!-- Final Stats -->
                    <div class="grid grid-cols-3 gap-6 mb-8">
                        <div class="rs-panel">
                            <div class="text-4xl font-bold text-green-600 dark:text-green-400 mb-2">
                                {{ correctCount }}
                            </div>
                            <div class="text-sm text-muted-foreground">Correct</div>
                        </div>
                        <div class="rs-panel">
                            <div class="text-4xl font-bold text-red-600 dark:text-red-400 mb-2">
                                {{ incorrectCount }}
                            </div>
                            <div class="text-sm text-muted-foreground">Incorrect</div>
                        </div>
                        <div class="rs-panel">
                            <div class="text-4xl font-bold text-primary mb-2">
                                {{ accuracy }}%
                            </div>
                            <div class="text-sm text-muted-foreground">Accuracy</div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4 justify-center">
                        <button
                            @click="goBack"
                            class="rs-button bg-secondary border-secondary text-secondary-foreground px-8 py-4 text-lg"
                        >
                            <ArrowLeft class="inline-block size-5 mr-2" />
                            Back to Flashcards
                        </button>
                        <button
                            @click="restart"
                            class="rs-button bg-primary border-primary text-primary-foreground px-8 py-4 text-lg"
                        >
                            <RotateCcw class="inline-block size-5 mr-2" />
                            Study Again
                        </button>
                    </div>
                </div>
            </div>

            <!-- No Flashcards -->
            <div v-else class="flex-1 flex items-center justify-center">
                <div class="text-center">
                    <div class="text-7xl mb-6">📚</div>
                    <h2 class="text-3xl font-bold mb-4 rs-heading">No Flashcards Yet</h2>
                    <p class="text-muted-foreground text-lg mb-6">
                        Create some flashcards first to start studying!
                    </p>
                    <button
                        @click="goBack"
                        class="rs-button bg-primary border-primary text-primary-foreground px-8 py-4 text-lg"
                    >
                        <ArrowLeft class="inline-block size-5 mr-2" />
                        Go to Flashcards
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
