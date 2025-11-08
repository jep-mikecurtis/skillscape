<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { AlertCircle } from 'lucide-vue-next';
import { ref, computed } from 'vue';

interface Skill {
    id: number;
    name: string;
    icon: string;
    xp_rate: number;
}

interface Props {
    skill: Skill;
}

const props = defineProps<Props>();
const isOpen = defineModel<boolean>('isOpen');
const emit = defineEmits<{
    success: [data: any];
}>();

const durationHours = ref<number>(0);
const durationMinutes = ref<number>(30);
const completedDate = ref<string>(new Date().toISOString().split('T')[0]);
const completedTime = ref<string>(new Date().toTimeString().slice(0, 5));
const notes = ref<string>('');
const error = ref<string>('');
const isSubmitting = ref(false);

const totalMinutes = computed(() => {
    return (durationHours.value * 60) + durationMinutes.value;
});

const estimatedXp = computed(() => {
    return totalMinutes.value * props.skill.xp_rate;
});

const isValidDuration = computed(() => {
    return totalMinutes.value > 0 && totalMinutes.value <= 480;
});

const resetForm = () => {
    durationHours.value = 0;
    durationMinutes.value = 30;
    completedDate.value = new Date().toISOString().split('T')[0];
    completedTime.value = new Date().toTimeString().slice(0, 5);
    notes.value = '';
    error.value = '';
};

const handleSubmit = async () => {
    if (!isValidDuration.value) {
        error.value = 'Duration must be between 1 minute and 8 hours';
        return;
    }

    isSubmitting.value = true;
    error.value = '';

    try {
        const completedAt = `${completedDate.value} ${completedTime.value}:00`;

        const response = await fetch('/api/time-entries/log-manual', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                skill_id: props.skill.id,
                duration_minutes: totalMinutes.value,
                completed_at: completedAt,
                notes: notes.value || null,
            }),
        });

        const data = await response.json();

        if (response.ok) {
            emit('success', data);
            isOpen.value = false;
            resetForm();
        } else {
            error.value = data.error || data.message || 'Failed to log time entry';
        }
    } catch (err) {
        console.error('Error logging time:', err);
        error.value = 'Failed to log time entry. Please try again.';
    } finally {
        isSubmitting.value = false;
    }
};

const handleClose = () => {
    if (!isSubmitting.value) {
        isOpen.value = false;
        resetForm();
    }
};
</script>

<template>
    <Dialog v-model:open="isOpen" @update:open="(open) => !open && handleClose()">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle class="flex items-center gap-2">
                    <span class="text-2xl">{{ skill.icon }}</span>
                    Log Time: {{ skill.name }}
                </DialogTitle>
                <DialogDescription>
                    Manually log time you spent practicing this skill.
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div
                    v-if="error"
                    class="flex items-start gap-2 rounded-lg bg-destructive/10 p-3 text-sm text-destructive"
                >
                    <AlertCircle class="h-4 w-4 mt-0.5 flex-shrink-0" />
                    <span>{{ error }}</span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <Label for="hours">Hours</Label>
                        <Input
                            id="hours"
                            v-model.number="durationHours"
                            type="number"
                            min="0"
                            max="8"
                            class="mt-1.5"
                        />
                    </div>
                    <div>
                        <Label for="minutes">Minutes</Label>
                        <Input
                            id="minutes"
                            v-model.number="durationMinutes"
                            type="number"
                            min="0"
                            max="59"
                            step="5"
                            class="mt-1.5"
                        />
                    </div>
                </div>

                <div
                    v-if="totalMinutes > 0"
                    class="text-sm text-muted-foreground text-center py-2 bg-muted rounded-lg"
                >
                    <span class="font-medium">{{ totalMinutes }} minutes</span>
                    = Estimated
                    <span class="font-medium text-green-600 dark:text-green-400">
                        +{{ estimatedXp.toLocaleString() }} XP
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <Label for="date">Date Completed</Label>
                        <Input
                            id="date"
                            v-model="completedDate"
                            type="date"
                            :max="new Date().toISOString().split('T')[0]"
                            :min="new Date(Date.now() - 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]"
                            class="mt-1.5"
                        />
                    </div>
                    <div>
                        <Label for="time">Time Completed</Label>
                        <Input
                            id="time"
                            v-model="completedTime"
                            type="time"
                            class="mt-1.5"
                        />
                    </div>
                </div>

                <div>
                    <Label for="notes">Notes (Optional)</Label>
                    <textarea
                        id="notes"
                        v-model="notes"
                        placeholder="What did you work on?"
                        maxlength="500"
                        class="mt-1.5 w-full rounded-lg border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                        rows="3"
                    />
                    <div class="text-xs text-muted-foreground mt-1 text-right">
                        {{ notes.length }}/500
                    </div>
                </div>

                <div class="rounded-lg bg-blue-50 dark:bg-blue-950/30 p-3 text-xs text-blue-900 dark:text-blue-100">
                    <strong>Limits:</strong>
                    <ul class="mt-1 space-y-0.5 list-disc list-inside">
                        <li>Maximum 8 hours per entry</li>
                        <li>Maximum 12 hours per skill per day</li>
                        <li>Can only log time within the last 7 days</li>
                    </ul>
                </div>

                <div class="flex gap-3">
                    <Button
                        type="button"
                        variant="outline"
                        @click="handleClose"
                        :disabled="isSubmitting"
                        class="flex-1"
                    >
                        Cancel
                    </Button>
                    <Button
                        type="submit"
                        :disabled="!isValidDuration || isSubmitting"
                        class="flex-1"
                    >
                        {{ isSubmitting ? 'Logging...' : 'Log Time' }}
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
