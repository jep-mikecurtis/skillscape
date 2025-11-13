<script setup lang="ts">
import { onMounted, ref } from 'vue';

const showInstallPrompt = ref(false);
const deferredPrompt = ref<BeforeInstallPromptEvent | null>(null);
const isIOS = ref(false);
const isStandalone = ref(false);

interface BeforeInstallPromptEvent extends Event {
    prompt: () => Promise<void>;
    userChoice: Promise<{ outcome: 'accepted' | 'dismissed' }>;
}

onMounted(() => {
    // Check if already installed
    isStandalone.value =
        window.matchMedia('(display-mode: standalone)').matches ||
        (window.navigator as any).standalone === true;

    // Detect iOS
    isIOS.value = /iPad|iPhone|iPod/.test(navigator.userAgent) && !(window as any).MSStream;

    // Listen for the beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (e: Event) => {
        e.preventDefault();
        deferredPrompt.value = e as BeforeInstallPromptEvent;
        showInstallPrompt.value = true;
    });

    // For iOS, show prompt if not standalone
    if (isIOS.value && !isStandalone.value) {
        showInstallPrompt.value = true;
    }
});

async function handleInstall() {
    if (!deferredPrompt.value) {
        return;
    }

    deferredPrompt.value.prompt();

    const { outcome } = await deferredPrompt.value.userChoice;

    if (outcome === 'accepted') {
        showInstallPrompt.value = false;
    }

    deferredPrompt.value = null;
}

function dismissPrompt() {
    showInstallPrompt.value = false;
}
</script>

<template>
    <div
        v-if="showInstallPrompt && !isStandalone"
        class="fixed bottom-4 right-4 z-50 max-w-sm rounded-lg border border-gray-200 bg-white p-4 shadow-lg dark:border-gray-700 dark:bg-gray-800"
    >
        <div class="flex items-start gap-3">
            <div class="shrink-0 rounded-lg bg-[#D4AF37]/10 p-2">
                <svg
                    class="h-6 w-6 text-[#D4AF37]"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                    />
                </svg>
            </div>

            <div class="flex-1">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                    Install SkillScape
                </h3>
                <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                    <template v-if="isIOS">
                        Tap
                        <svg
                            class="mx-1 inline-block h-4 w-4"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"
                            />
                        </svg>
                        then "Add to Home Screen"
                    </template>
                    <template v-else>
                        Add this app to your home screen for quick access and a better
                        experience.
                    </template>
                </p>

                <div class="mt-3 flex gap-2">
                    <button
                        v-if="!isIOS"
                        type="button"
                        class="rounded-md bg-[#D4AF37] px-3 py-1.5 text-xs font-medium text-white hover:bg-[#B8941F] focus:outline-none focus:ring-2 focus:ring-[#D4AF37] focus:ring-offset-2"
                        @click="handleInstall"
                    >
                        Install
                    </button>
                    <button
                        type="button"
                        class="rounded-md px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:text-gray-300 dark:hover:bg-gray-700"
                        @click="dismissPrompt"
                    >
                        Dismiss
                    </button>
                </div>
            </div>

            <button
                type="button"
                class="shrink-0 rounded-md p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:hover:text-gray-300"
                @click="dismissPrompt"
            >
                <span class="sr-only">Close</span>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>
    </div>
</template>
