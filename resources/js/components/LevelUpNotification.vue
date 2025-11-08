<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Sparkles, Trophy } from 'lucide-vue-next';
import { computed, watch } from 'vue';

interface LevelUpData {
    leveled_up: boolean;
    old_level: number;
    new_level: number;
    skill: {
        id: number;
        name: string;
        icon: string;
    };
}

interface Props {
    levelUpData: LevelUpData | null;
}

const props = defineProps<Props>();
const isOpen = defineModel<boolean>('isOpen');

const getLevelMilestoneMessage = (level: number): string | null => {
    if (level === 10) return 'You\'re on a roll!';
    if (level === 25) return 'Quarter century!';
    if (level === 50) return 'Halfway to mastery!';
    if (level === 75) return 'You\'re a pro now!';
    if (level === 99) return 'Maximum level achieved!';
    if (level === 120) return 'You are a true master!';
    return null;
};

const milestoneMessage = computed(() => {
    if (!props.levelUpData) return null;
    return getLevelMilestoneMessage(props.levelUpData.new_level);
});

watch(
    () => props.levelUpData,
    (newData) => {
        if (newData?.leveled_up) {
            isOpen.value = true;
        }
    },
    { immediate: true }
);

const handleClose = () => {
    isOpen.value = false;
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle class="flex items-center gap-2 text-2xl">
                    <Trophy class="h-6 w-6 text-yellow-500" />
                    Level Up!
                </DialogTitle>
                <DialogDescription>
                    Congratulations on your progress!
                </DialogDescription>
            </DialogHeader>

            <div
                v-if="levelUpData"
                class="flex flex-col items-center gap-6 py-6"
            >
                <div class="relative">
                    <div
                        class="text-6xl animate-bounce"
                        style="animation-duration: 0.6s; animation-iteration-count: 3"
                    >
                        {{ levelUpData.skill.icon }}
                    </div>
                    <Sparkles
                        class="absolute -top-2 -right-2 h-8 w-8 text-yellow-500 animate-pulse"
                    />
                </div>

                <div class="text-center space-y-2">
                    <h3 class="text-xl font-semibold">
                        {{ levelUpData.skill.name }}
                    </h3>
                    <p class="text-3xl font-bold text-primary">
                        Level {{ levelUpData.old_level }}
                        <span class="text-muted-foreground">→</span>
                        Level {{ levelUpData.new_level }}
                    </p>
                    <p
                        v-if="milestoneMessage"
                        class="text-lg text-yellow-600 dark:text-yellow-400 font-medium"
                    >
                        {{ milestoneMessage }}
                    </p>
                </div>

                <div
                    class="w-full p-4 bg-muted rounded-lg text-center space-y-1"
                >
                    <p class="text-sm text-muted-foreground">
                        Keep up the great work!
                    </p>
                    <p class="text-xs text-muted-foreground">
                        Your dedication is paying off.
                    </p>
                </div>

                <Button @click="handleClose" class="w-full"> Continue </Button>
            </div>
        </DialogContent>
    </Dialog>
</template>
