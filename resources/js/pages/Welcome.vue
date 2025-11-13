<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { Sword, Target, TrendingUp, Trophy, Zap, Shield, Menu, X, ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { onMounted, onUnmounted, ref } from 'vue';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const mobileMenuOpen = ref(false);

// Demo animation state
let demoInterval: number | null = null;
let demoSeconds = 932; // Start at 15:32
let currentXp = 7523;
const baseXp = 7523;
const xpRate = 40; // XP per minute

// Carousel states
const currentDemoSlide = ref(0);
const currentSkillSlide = ref(0);
const demoAutoPlayInterval = ref<number | null>(null);
const skillAutoPlayInterval = ref<number | null>(null);

// Demo carousel slides
const demoSlides = [
    {
        title: 'Time Tracking',
        emoji: '💻',
        skillName: 'Web Development',
        level: 42,
        description: 'Master the art of building amazing websites',
    },
    {
        title: 'Flashcard Study',
        emoji: '📚',
        skillName: 'Spanish Language',
        level: 18,
        description: 'Learn vocabulary and grammar with flashcards',
    },
    {
        title: 'Progress Tracking',
        emoji: '🎸',
        skillName: 'Guitar Playing',
        level: 28,
        description: 'Track your practice sessions and improvement',
    },
];

// Skills carousel slides
const skillSlides = [
    {
        category: 'Technology',
        skills: [
            { emoji: '💻', name: 'Web Development', level: 42, xp: 7523, maxXp: 10000, progress: 75 },
            { emoji: '🤖', name: 'Machine Learning', level: 15, xp: 890, maxXp: 4500, progress: 20 },
            { emoji: '📱', name: 'Mobile Apps', level: 33, xp: 5240, maxXp: 9000, progress: 58 },
        ],
    },
    {
        category: 'Creative Arts',
        skills: [
            { emoji: '🎸', name: 'Guitar Playing', level: 28, xp: 3240, maxXp: 8000, progress: 40 },
            { emoji: '🎨', name: 'Digital Art', level: 22, xp: 4100, maxXp: 7000, progress: 59 },
            { emoji: '📸', name: 'Photography', level: 19, xp: 2850, maxXp: 6000, progress: 48 },
        ],
    },
    {
        category: 'Fitness & Health',
        skills: [
            { emoji: '🏋️', name: 'Strength Training', level: 15, xp: 890, maxXp: 4500, progress: 20 },
            { emoji: '🧘', name: 'Yoga', level: 24, xp: 3600, maxXp: 7500, progress: 48 },
            { emoji: '🏃', name: 'Running', level: 31, xp: 6200, maxXp: 8500, progress: 73 },
        ],
    },
];

// Demo carousel navigation
const nextDemoSlide = () => {
    currentDemoSlide.value = (currentDemoSlide.value + 1) % demoSlides.length;
    resetDemoAutoPlay();
};

const prevDemoSlide = () => {
    currentDemoSlide.value = currentDemoSlide.value === 0 ? demoSlides.length - 1 : currentDemoSlide.value - 1;
    resetDemoAutoPlay();
};

const goToDemoSlide = (index: number) => {
    currentDemoSlide.value = index;
    resetDemoAutoPlay();
};

const resetDemoAutoPlay = () => {
    if (demoAutoPlayInterval.value) {
        clearInterval(demoAutoPlayInterval.value);
    }
    demoAutoPlayInterval.value = window.setInterval(() => {
        nextDemoSlide();
    }, 5000);
};

// Skills carousel navigation
const nextSkillSlide = () => {
    currentSkillSlide.value = (currentSkillSlide.value + 1) % skillSlides.length;
    resetSkillAutoPlay();
};

const prevSkillSlide = () => {
    currentSkillSlide.value = currentSkillSlide.value === 0 ? skillSlides.length - 1 : currentSkillSlide.value - 1;
    resetSkillAutoPlay();
};

const goToSkillSlide = (index: number) => {
    currentSkillSlide.value = index;
    resetSkillAutoPlay();
};

const resetSkillAutoPlay = () => {
    if (skillAutoPlayInterval.value) {
        clearInterval(skillAutoPlayInterval.value);
    }
    skillAutoPlayInterval.value = window.setInterval(() => {
        nextSkillSlide();
    }, 5000);
};

// Demo animation functions
const startDemo = () => {
    if (demoInterval) clearInterval(demoInterval);

    demoSeconds = 932;
    currentXp = baseXp;

    demoInterval = window.setInterval(() => {
        demoSeconds++;

        // Calculate XP gain (40 XP per minute = ~0.67 XP per second)
        currentXp = baseXp + Math.floor((demoSeconds - 932) * (xpRate / 60));

        const timerEl = document.querySelector('.demo-timer');
        const xpEl = document.querySelector('.demo-xp');
        const currentXpEl = document.querySelector('.demo-current-xp');
        const progressBar = document.querySelector('.demo-progress-bar') as HTMLElement;
        const progressText = document.querySelector('.demo-progress-text');
        const levelUp = document.querySelector('.demo-levelup');
        const progressCard = document.querySelector('.demo-levelup')?.parentElement;

        if (timerEl) {
            const minutes = Math.floor(demoSeconds / 60);
            const seconds = demoSeconds % 60;
            timerEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }

        if (xpEl) {
            xpEl.textContent = `+${currentXp - baseXp} XP`;
        }

        if (currentXpEl) {
            currentXpEl.textContent = currentXp.toLocaleString();
        }

        // Update progress bar
        const progress = Math.min(((currentXp / 10000) * 100), 100);
        if (progressBar) {
            progressBar.style.width = `${progress}%`;
        }

        if (progressText) {
            progressText.textContent = `${Math.floor(progress)}%`;
        }

        // Level up animation at 100%
        if (currentXp >= 10000 && levelUp && progressCard) {
            progressCard.style.display = 'none';
            levelUp.classList.remove('hidden');

            // Reset after 3 seconds
            setTimeout(() => {
                if (demoInterval) clearInterval(demoInterval);
                setTimeout(startDemo, 1000);
            }, 3000);
        }

        // Auto-restart after reaching end
        if (demoSeconds > 1500) { // After ~25 minutes of demo time
            if (demoInterval) clearInterval(demoInterval);
            setTimeout(startDemo, 1000);
        }
    }, 100); // Update every 100ms for smooth animation
};

const restartDemo = () => {
    // Reset all elements
    const levelUp = document.querySelector('.demo-levelup');
    const progressCard = document.querySelector('.demo-levelup')?.parentElement;

    if (levelUp) levelUp.classList.add('hidden');
    if (progressCard) progressCard.style.display = 'block';

    startDemo();
};

onMounted(() => {
    // Start demo automatically when component mounts
    setTimeout(startDemo, 1000);

    // Add click handler for restart button
    const restartBtn = document.querySelector('.demo-restart');
    if (restartBtn) {
        restartBtn.addEventListener('click', restartDemo);
    }

    // Start carousel auto-play
    resetDemoAutoPlay();
    resetSkillAutoPlay();
});

onUnmounted(() => {
    if (demoInterval) {
        clearInterval(demoInterval);
    }
    if (demoAutoPlayInterval.value) {
        clearInterval(demoAutoPlayInterval.value);
    }
    if (skillAutoPlayInterval.value) {
        clearInterval(skillAutoPlayInterval.value);
    }
});

const features = [
    {
        icon: Target,
        title: 'Track Any Skill',
        description: 'From coding to cooking, music to martial arts. Level up in every area of your life.',
    },
    {
        icon: TrendingUp,
        title: 'Watch Progress Grow',
        description: 'Visual progress bars and XP tracking make your improvement tangible and rewarding.',
    },
    {
        icon: Trophy,
        title: 'Level Up System',
        description: 'Gain experience points and level up as you practice. Each level unlocks new milestones.',
    },
    {
        icon: Zap,
        title: 'Real-Time Sessions',
        description: 'Track training sessions with a built-in timer. Every minute counts toward your mastery.',
    },
];

const howItWorks = [
    {
        step: '1',
        title: 'Choose Your Skills',
        description: 'Select the skills you want to master. Start with a few or go all in.',
        icon: Target,
    },
    {
        step: '2',
        title: 'Start Training',
        description: 'Use the timer to track your practice sessions. Every minute earns XP.',
        icon: Sword,
    },
    {
        step: '3',
        title: 'Level Up',
        description: 'Watch your skills level up as you gain experience. Celebrate your progress!',
        icon: TrendingUp,
    },
    {
        step: '4',
        title: 'Become Legendary',
        description: 'Reach level 99 and beyond. Build yourself into the person you want to be.',
        icon: Shield,
    },
];
</script>

<template>
    <Head title="Welcome to Skillscape">
        <meta name="description" content="Level up your life like an RPG. Track skills, gain XP, and master your journey with Skillscape." />
    </Head>

    <div class="min-h-screen bg-background">
        <!-- Navigation -->
        <nav class="border-b-2 border-border/50 bg-card/50 backdrop-blur-sm sticky top-0 z-50 shadow-lg">
            <div class="container mx-auto px-4 py-4">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="size-8 sm:size-10 flex items-center justify-center rounded-sm bg-gradient-to-br from-primary to-accent border-2 border-primary/50 shadow-lg">
                            <AppLogoIcon class="size-5 sm:size-7 text-primary-foreground" />
                        </div>
                        <span class="text-lg sm:text-2xl font-bold rs-gold-text" style="font-family: 'Cinzel', serif; letter-spacing: 0.05em;">
                            SKILLSCAPE
                        </span>
                    </div>

                    <!-- Desktop Auth Links -->
                    <div class="hidden md:flex items-center gap-3">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="dashboard()"
                            class="rs-button bg-primary border-primary text-primary-foreground"
                        >
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link
                                :href="login()"
                                class="rs-button bg-secondary border-secondary text-secondary-foreground"
                            >
                                Log In
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="register()"
                                class="rs-button bg-primary border-primary text-primary-foreground"
                            >
                                Start Your Journey
                            </Link>
                        </template>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button
                        class="md:hidden rs-button bg-secondary border-secondary text-secondary-foreground p-2"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        :aria-label="mobileMenuOpen ? 'Close menu' : 'Open menu'"
                    >
                        <Menu v-if="!mobileMenuOpen" class="size-5" />
                        <X v-else class="size-5" />
                    </button>
                </div>

                <!-- Mobile Menu -->
                <Transition
                    enter-active-class="transition-all duration-200 ease-out"
                    enter-from-class="opacity-0 -translate-y-2"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition-all duration-150 ease-in"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-2"
                >
                    <div v-if="mobileMenuOpen" class="md:hidden pt-4 pb-2">
                        <div class="flex flex-col gap-2">
                            <Link
                                v-if="$page.props.auth.user"
                                :href="dashboard()"
                                class="rs-button bg-primary border-primary text-primary-foreground w-full text-center"
                                @click="mobileMenuOpen = false"
                            >
                                Dashboard
                            </Link>
                            <template v-else>
                                <Link
                                    :href="login()"
                                    class="rs-button bg-secondary border-secondary text-secondary-foreground w-full text-center"
                                    @click="mobileMenuOpen = false"
                                >
                                    Log In
                                </Link>
                                <Link
                                    v-if="canRegister"
                                    :href="register()"
                                    class="rs-button bg-primary border-primary text-primary-foreground w-full text-center"
                                    @click="mobileMenuOpen = false"
                                >
                                    Start Your Journey
                                </Link>
                            </template>
                        </div>
                    </div>
                </Transition>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative py-20 lg:py-32 overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, var(--border) 10px, var(--border) 11px);"></div>
            </div>

            <!-- Animated Crossed Swords - Top Left -->
            <div class="absolute top-10 left-10 opacity-20 hidden lg:block">
                <div class="relative w-32 h-32">
                    <!-- Left Sword -->
                    <svg class="absolute sword-left sword-glow" width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M40 5 L37 50 L43 50 Z" fill="currentColor" class="text-primary" />
                        <rect x="30" y="49" width="20" height="4" fill="currentColor" class="text-accent" />
                        <rect x="37" y="53" width="6" height="15" rx="1" fill="currentColor" class="text-primary" />
                        <circle cx="40" cy="70" r="4" fill="currentColor" class="text-accent" />
                    </svg>

                    <!-- Right Sword -->
                    <svg class="absolute sword-right sword-glow" width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M40 5 L37 50 L43 50 Z" fill="currentColor" class="text-primary" />
                        <rect x="30" y="49" width="20" height="4" fill="currentColor" class="text-accent" />
                        <rect x="37" y="53" width="6" height="15" rx="1" fill="currentColor" class="text-primary" />
                        <circle cx="40" cy="70" r="4" fill="currentColor" class="text-accent" />
                    </svg>

                    <!-- Clash Spark -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 clash-spark">
                        <div class="w-4 h-4 bg-primary rounded-full blur-sm"></div>
                    </div>
                </div>
            </div>

            <!-- Animated Crossed Swords - Top Right -->
            <div class="absolute top-10 right-10 opacity-20 hidden lg:block">
                <div class="relative w-32 h-32">
                    <!-- Left Sword -->
                    <svg class="absolute sword-left sword-glow" width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M40 5 L37 50 L43 50 Z" fill="currentColor" class="text-primary" />
                        <rect x="30" y="49" width="20" height="4" fill="currentColor" class="text-accent" />
                        <rect x="37" y="53" width="6" height="15" rx="1" fill="currentColor" class="text-primary" />
                        <circle cx="40" cy="70" r="4" fill="currentColor" class="text-accent" />
                    </svg>

                    <!-- Right Sword -->
                    <svg class="absolute sword-right sword-glow" width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M40 5 L37 50 L43 50 Z" fill="currentColor" class="text-primary" />
                        <rect x="30" y="49" width="20" height="4" fill="currentColor" class="text-accent" />
                        <rect x="37" y="53" width="6" height="15" rx="1" fill="currentColor" class="text-primary" />
                        <circle cx="40" cy="70" r="4" fill="currentColor" class="text-accent" />
                    </svg>

                    <!-- Clash Spark -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 clash-spark">
                        <div class="w-4 h-4 bg-primary rounded-full blur-sm"></div>
                    </div>
                </div>
            </div>

            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-4xl mx-auto text-center">
                    <!-- Main Badge -->
                    <div class="inline-flex items-center gap-2 px-4 py-2 mb-8 rs-panel float-animation">
                        <Zap class="size-4 text-primary" />
                        <span class="text-sm font-semibold">Level Up Your Life</span>
                    </div>

                    <!-- Hero Title -->
                    <h1 class="text-5xl md:text-7xl font-bold mb-6 rs-heading" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                        Master Your Skills.<br />
                        Track Your Progress.
                    </h1>

                    <!-- Subtitle -->
                    <p class="text-xl md:text-2xl text-muted-foreground mb-8 max-w-2xl mx-auto leading-relaxed">
                        Turn your real life into an RPG. Track any skill, gain experience points,
                        level up, and watch yourself grow. It's time to become legendary.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="register()"
                            class="rs-button rs-button-glow bg-primary border-primary text-primary-foreground text-lg px-8 py-3"
                        >
                            <Sword class="inline-block size-5 mr-2" />
                            Begin Your Quest
                        </Link>
                        <Link
                            v-else
                            :href="dashboard()"
                            class="rs-button rs-button-glow bg-primary border-primary text-primary-foreground text-lg px-8 py-3"
                        >
                            <Sword class="inline-block size-5 mr-2" />
                            Go to Dashboard
                        </Link>
                        <a
                            href="#how-it-works"
                            class="rs-button bg-secondary border-secondary text-secondary-foreground text-lg px-8 py-3"
                        >
                            How It Works
                        </a>
                    </div>

                    <!-- Stats Preview -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                        <div class="rs-card p-6">
                            <div class="text-3xl font-bold rs-gold-text mb-1">∞</div>
                            <div class="text-sm text-muted-foreground">Skills to Master</div>
                        </div>
                        <div class="rs-card p-6">
                            <div class="text-3xl font-bold rs-gold-text mb-1">99+</div>
                            <div class="text-sm text-muted-foreground">Levels to Achieve</div>
                        </div>
                        <div class="rs-card p-6">
                            <div class="text-3xl font-bold rs-gold-text mb-1">100%</div>
                            <div class="text-sm text-muted-foreground">Your Potential</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Interactive Demo Section -->
        <section class="py-20 bg-gradient-to-b from-background to-card/30">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <!-- Section Header -->
                    <div class="text-center mb-12">
                        <h2 class="text-4xl md:text-5xl font-bold mb-4 rs-heading">
                            See It In Action
                        </h2>
                        <p class="text-xl text-muted-foreground max-w-2xl mx-auto">
                            Watch your skills come to life with real-time tracking and progression
                        </p>
                    </div>

                    <!-- Demo Container -->
                    <div class="relative max-w-4xl mx-auto">
                        <!-- Carousel Navigation Buttons -->
                        <button
                            @click="prevDemoSlide"
                            class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 z-10 rs-button bg-card border-primary/30 text-primary p-3 rounded-full hover:bg-primary hover:text-primary-foreground transition-all shadow-lg hidden md:flex items-center justify-center"
                            aria-label="Previous demo"
                        >
                            <ChevronLeft class="size-6" />
                        </button>
                        <button
                            @click="nextDemoSlide"
                            class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 z-10 rs-button bg-card border-primary/30 text-primary p-3 rounded-full hover:bg-primary hover:text-primary-foreground transition-all shadow-lg hidden md:flex items-center justify-center"
                            aria-label="Next demo"
                        >
                            <ChevronRight class="size-6" />
                        </button>

                        <!-- Browser Chrome -->
                        <div class="rounded-t-xl border-2 border-primary/30 bg-card/50 backdrop-blur-sm overflow-hidden shadow-2xl">
                            <!-- Browser Header -->
                            <div class="flex items-center gap-2 px-4 py-3 bg-card border-b border-border/50">
                                <div class="flex gap-1.5">
                                    <div class="w-3 h-3 rounded-full bg-red-500/70"></div>
                                    <div class="w-3 h-3 rounded-full bg-yellow-500/70"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-500/70"></div>
                                </div>
                                <div class="flex-1 mx-4">
                                    <div class="text-xs text-muted-foreground bg-background/50 rounded px-3 py-1 text-center max-w-sm mx-auto">
                                        skillscape.app/skills/{{ demoSlides[currentDemoSlide].skillName.toLowerCase().replace(' ', '-') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Demo Content with Transition -->
                            <div class="relative bg-background" style="min-height: 500px;">
                                <TransitionGroup
                                    enter-active-class="transition-all duration-300 ease-out"
                                    enter-from-class="opacity-0 translate-x-8"
                                    enter-to-class="opacity-100 translate-x-0"
                                    leave-active-class="transition-all duration-300 ease-in absolute inset-0"
                                    leave-from-class="opacity-100 translate-x-0"
                                    leave-to-class="opacity-0 -translate-x-8"
                                >
                                    <div :key="currentDemoSlide" class="p-4 sm:p-8">
                                        <!-- Skill Header -->
                                        <div class="flex items-start gap-4 mb-6">
                                            <div class="text-5xl">{{ demoSlides[currentDemoSlide].emoji }}</div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <h3 class="text-2xl sm:text-3xl font-bold">{{ demoSlides[currentDemoSlide].skillName }}</h3>
                                                    <div class="rounded-full bg-primary/10 px-3 py-1 text-sm font-bold text-primary animate-pulse">
                                                        Level {{ demoSlides[currentDemoSlide].level }}
                                                    </div>
                                                </div>
                                                <p class="text-muted-foreground text-sm">{{ demoSlides[currentDemoSlide].description }}</p>
                                            </div>
                                        </div>

                                        <!-- Time Tracker Card -->
                                        <div class="rounded-xl border border-sidebar-border/70 bg-card p-6 mb-6">
                                            <h4 class="font-semibold mb-4">Time Tracker</h4>

                                            <!-- Timer Display -->
                                            <div class="rounded-lg bg-secondary/50 p-8 text-center mb-4">
                                                <div class="font-mono text-5xl font-bold tracking-wider text-green-600 dark:text-green-400 demo-timer">
                                                    00:15:32
                                                </div>
                                                <div class="text-muted-foreground mt-2 text-sm">
                                                    Estimated: <span class="demo-xp text-green-600 dark:text-green-400">+620 XP</span>
                                                </div>
                                            </div>

                                            <!-- Stop Button -->
                                            <button class="w-full rounded-lg bg-red-600 px-6 py-4 text-lg font-semibold text-white demo-button">
                                                Stop Training
                                            </button>
                                        </div>

                                        <!-- Progress Card -->
                                        <div class="rounded-xl border border-sidebar-border/70 bg-card p-6">
                                            <h4 class="font-semibold mb-4">Level Progress</h4>

                                            <div class="space-y-2 mb-4">
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-muted-foreground">XP Progress</span>
                                                    <span class="font-medium"><span class="demo-current-xp">7,523</span> / 10,000</span>
                                                </div>
                                                <div class="relative h-4 w-full overflow-hidden rounded-full bg-secondary">
                                                    <div class="demo-progress-bar h-full rounded-full bg-gradient-to-r from-blue-500 to-purple-500 transition-all duration-1000" style="width: 75%"></div>
                                                </div>
                                                <div class="text-center text-sm font-medium">
                                                    <span class="demo-progress-text">75%</span> to Level 43
                                                </div>
                                            </div>

                                            <!-- Level Up Animation -->
                                            <div class="demo-levelup hidden">
                                                <div class="text-center py-8 animate-bounce">
                                                    <div class="text-5xl mb-2">🎉</div>
                                                    <div class="text-2xl font-bold rs-gold-text mb-1">LEVEL UP!</div>
                                                    <div class="text-lg text-primary">Level 43 Achieved</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </TransitionGroup>
                            </div>
                        </div>

                        <!-- Floating Labels -->
                        <div class="absolute -right-4 top-1/4 hidden lg:block animate-float">
                            <div class="bg-primary text-primary-foreground px-4 py-2 rounded-lg shadow-lg font-semibold text-sm">
                                ⚡ Real-time XP Tracking
                            </div>
                        </div>
                        <div class="absolute -left-4 bottom-1/3 hidden lg:block animate-float-delayed">
                            <div class="bg-accent text-accent-foreground px-4 py-2 rounded-lg shadow-lg font-semibold text-sm">
                                📊 Visual Progress
                            </div>
                        </div>
                    </div>

                    <!-- Carousel Indicators -->
                    <div class="flex justify-center gap-2 mt-6">
                        <button
                            v-for="(slide, index) in demoSlides"
                            :key="index"
                            @click="goToDemoSlide(index)"
                            class="transition-all duration-300"
                            :class="[
                                currentDemoSlide === index
                                    ? 'w-8 h-2 bg-primary rounded-full'
                                    : 'w-2 h-2 bg-muted-foreground/30 rounded-full hover:bg-muted-foreground/50'
                            ]"
                            :aria-label="`Go to demo ${index + 1}: ${slide.title}`"
                        />
                    </div>

                    <!-- Demo Controls -->
                    <div class="text-center mt-8">
                        <p class="text-sm text-muted-foreground mb-4">Interactive Demo • Auto-plays every 5 seconds</p>
                        <button class="demo-restart rs-button bg-primary border-primary text-primary-foreground px-6 py-2">
                            🔄 Restart Animation
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Skills & Resources Demo Section -->
        <section class="py-20 bg-card/30">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <!-- Section Header -->
                    <div class="text-center mb-12">
                        <h2 class="text-4xl md:text-5xl font-bold mb-4 rs-heading">
                            Your Skills, Your Way
                        </h2>
                        <p class="text-xl text-muted-foreground max-w-2xl mx-auto">
                            Track unlimited skills with built-in resources to guide your journey
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Skills Dashboard Preview with Carousel -->
                        <div class="relative space-y-4">
                            <!-- Carousel Navigation -->
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-2xl font-bold rs-heading text-center lg:text-left">Track Any Skill</h3>
                                <div class="flex gap-2">
                                    <button
                                        @click="prevSkillSlide"
                                        class="rs-button bg-card border-primary/30 text-primary p-2 rounded-full hover:bg-primary hover:text-primary-foreground transition-all"
                                        aria-label="Previous skills"
                                    >
                                        <ChevronLeft class="size-4" />
                                    </button>
                                    <button
                                        @click="nextSkillSlide"
                                        class="rs-button bg-card border-primary/30 text-primary p-2 rounded-full hover:bg-primary hover:text-primary-foreground transition-all"
                                        aria-label="Next skills"
                                    >
                                        <ChevronRight class="size-4" />
                                    </button>
                                </div>
                            </div>

                            <!-- Skills Carousel -->
                            <div class="relative overflow-hidden" style="min-height: 380px;">
                                <TransitionGroup
                                    enter-active-class="transition-all duration-300 ease-out"
                                    enter-from-class="opacity-0 translate-x-8"
                                    enter-to-class="opacity-100 translate-x-0"
                                    leave-active-class="transition-all duration-300 ease-in absolute inset-0"
                                    leave-from-class="opacity-100 translate-x-0"
                                    leave-to-class="opacity-0 -translate-x-8"
                                >
                                    <div :key="currentSkillSlide" class="space-y-4">
                                        <div class="text-center lg:text-left mb-4">
                                            <span class="text-sm font-semibold text-primary">{{ skillSlides[currentSkillSlide].category }}</span>
                                        </div>

                                        <!-- Skill Cards -->
                                        <div
                                            v-for="(skill, index) in skillSlides[currentSkillSlide].skills"
                                            :key="index"
                                            class="rs-panel group hover:border-primary/50 transition-all cursor-pointer"
                                        >
                                            <div class="flex items-start gap-4">
                                                <div class="text-4xl">{{ skill.emoji }}</div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-3 mb-2">
                                                        <h4 class="text-xl font-bold">{{ skill.name }}</h4>
                                                        <div class="rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-bold text-primary">
                                                            Level {{ skill.level }}
                                                        </div>
                                                    </div>
                                                    <!-- Progress Bar -->
                                                    <div class="space-y-1">
                                                        <div class="flex justify-between text-xs">
                                                            <span class="text-muted-foreground">{{ skill.xp.toLocaleString() }} / {{ skill.maxXp.toLocaleString() }} XP</span>
                                                            <span class="font-medium">{{ skill.progress }}%</span>
                                                        </div>
                                                        <div class="relative h-2 w-full overflow-hidden rounded-full bg-secondary">
                                                            <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-purple-500" :style="`width: ${skill.progress}%`"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </TransitionGroup>
                            </div>

                            <!-- Carousel Indicators -->
                            <div class="flex justify-center gap-2 pt-4">
                                <button
                                    v-for="(slide, index) in skillSlides"
                                    :key="index"
                                    @click="goToSkillSlide(index)"
                                    class="transition-all duration-300"
                                    :class="[
                                        currentSkillSlide === index
                                            ? 'w-8 h-2 bg-primary rounded-full'
                                            : 'w-2 h-2 bg-muted-foreground/30 rounded-full hover:bg-muted-foreground/50'
                                    ]"
                                    :aria-label="`Go to ${slide.category} skills`"
                                />
                            </div>

                            <div class="text-center lg:text-left pt-4">
                                <p class="text-sm text-muted-foreground italic">
                                    + Track unlimited skills in any category
                                </p>
                            </div>
                        </div>

                        <!-- Learning Resources Preview -->
                        <div class="space-y-4">
                            <h3 class="text-2xl font-bold mb-4 rs-heading text-center lg:text-left">Curated Resources</h3>

                            <div class="rs-panel">
                                <h4 class="font-semibold mb-4 flex items-center gap-2">
                                    <span class="text-2xl">💻</span>
                                    Web Development Resources
                                </h4>

                                <div class="space-y-3">
                                    <!-- Resource 1 -->
                                    <a href="#" class="flex items-center gap-3 rounded-lg border border-sidebar-border/50 p-3 transition-colors hover:bg-accent hover:border-primary group">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-500/10 text-red-600">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-medium text-sm group-hover:text-primary transition-colors">
                                                JavaScript Complete Guide
                                            </div>
                                            <div class="text-muted-foreground text-xs">YouTube Tutorial</div>
                                        </div>
                                        <svg class="h-4 w-4 text-muted-foreground shrink-0 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>

                                    <!-- Resource 2 -->
                                    <a href="#" class="flex items-center gap-3 rounded-lg border border-sidebar-border/50 p-3 transition-colors hover:bg-accent hover:border-primary group">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-500/10 text-blue-600">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-medium text-sm group-hover:text-primary transition-colors">
                                                MDN Web Docs
                                            </div>
                                            <div class="text-muted-foreground text-xs">Documentation</div>
                                        </div>
                                        <svg class="h-4 w-4 text-muted-foreground shrink-0 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>

                                    <!-- Resource 3 -->
                                    <a href="#" class="flex items-center gap-3 rounded-lg border border-sidebar-border/50 p-3 transition-colors hover:bg-accent hover:border-primary group">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-green-500/10 text-green-600">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-medium text-sm group-hover:text-primary transition-colors">
                                                Coding Challenges
                                            </div>
                                            <div class="text-muted-foreground text-xs">Interactive Practice</div>
                                        </div>
                                        <svg class="h-4 w-4 text-muted-foreground shrink-0 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>

                                    <!-- Resource 4 -->
                                    <a href="#" class="flex items-center gap-3 rounded-lg border border-sidebar-border/50 p-3 transition-colors hover:bg-accent hover:border-primary group">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-purple-500/10 text-purple-600">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-medium text-sm group-hover:text-primary transition-colors">
                                                Frontend Handbook
                                            </div>
                                            <div class="text-muted-foreground text-xs">Free eBook</div>
                                        </div>
                                        <svg class="h-4 w-4 text-muted-foreground shrink-0 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="text-center lg:text-left pt-4">
                                <p class="text-sm text-muted-foreground italic">
                                    Every skill comes with handpicked learning materials
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Feature Highlights -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                        <div class="text-center">
                            <div class="rs-skill-icon mx-auto mb-3">
                                <Target class="size-6 text-primary" />
                            </div>
                            <h4 class="font-bold mb-2">Unlimited Skills</h4>
                            <p class="text-sm text-muted-foreground">
                                Track as many skills as you want, from any category
                            </p>
                        </div>
                        <div class="text-center">
                            <div class="rs-skill-icon mx-auto mb-3">
                                <svg class="size-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <h4 class="font-bold mb-2">Quality Resources</h4>
                            <p class="text-sm text-muted-foreground">
                                Curated tutorials, docs, and tools for each skill
                            </p>
                        </div>
                        <div class="text-center">
                            <div class="rs-skill-icon mx-auto mb-3">
                                <TrendingUp class="size-6 text-primary" />
                            </div>
                            <h4 class="font-bold mb-2">Track Progress</h4>
                            <p class="text-sm text-muted-foreground">
                                See your improvement with visual XP and level bars
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="py-20 bg-card/30">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <!-- Section Header -->
                    <div class="text-center mb-16">
                        <h2 class="text-4xl md:text-5xl font-bold mb-4 rs-heading">
                            How to Level Up
                        </h2>
                        <p class="text-xl text-muted-foreground max-w-2xl mx-auto">
                            Four simple steps to transform your life into an epic adventure
                        </p>
                    </div>

                    <!-- Steps Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div
                            v-for="step in howItWorks"
                            :key="step.step"
                            class="rs-panel relative"
                        >
                            <!-- Step Number Badge -->
                            <div class="absolute -top-4 -left-4">
                                <div class="rs-level-badge text-lg size-12 rounded-full flex items-center justify-center">
                                    {{ step.step }}
                                </div>
                            </div>

                            <!-- Icon -->
                            <div class="rs-skill-icon mx-auto mb-4 mt-4">
                                <component :is="step.icon" class="size-6 text-primary" />
                            </div>

                            <!-- Content -->
                            <h3 class="text-xl font-bold mb-2 text-center" style="font-family: 'Cinzel', serif;">
                                {{ step.title }}
                            </h3>
                            <p class="text-muted-foreground text-center text-sm">
                                {{ step.description }}
                            </p>
                        </div>
                    </div>

                    <!-- XP Bar Demo -->
                    <div class="mt-16 max-w-2xl mx-auto">
                        <div class="rs-panel">
                            <h3 class="text-lg font-bold mb-4 text-center rs-gold-text">
                                Your Progress Looks Like This
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="font-semibold">⚔️ Coding</span>
                                        <span class="rs-level-badge">Level 42</span>
                                    </div>
                                    <div class="rs-xp-bar">
                                        <div class="rs-xp-bar-fill" style="width: 75%"></div>
                                        <div class="rs-xp-bar-text">75% to Level 43</div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="font-semibold">🎸 Guitar</span>
                                        <span class="rs-level-badge">Level 28</span>
                                    </div>
                                    <div class="rs-xp-bar">
                                        <div class="rs-xp-bar-fill" style="width: 40%"></div>
                                        <div class="rs-xp-bar-text">40% to Level 29</div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="font-semibold">🏋️ Fitness</span>
                                        <span class="rs-level-badge">Level 15</span>
                                    </div>
                                    <div class="rs-xp-bar">
                                        <div class="rs-xp-bar-fill" style="width: 20%"></div>
                                        <div class="rs-xp-bar-text">20% to Level 16</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <!-- Section Header -->
                    <div class="text-center mb-16">
                        <h2 class="text-4xl md:text-5xl font-bold mb-4 rs-heading">
                            Epic Features
                        </h2>
                        <p class="text-xl text-muted-foreground max-w-2xl mx-auto">
                            Everything you need to turn your life into an adventure
                        </p>
                    </div>

                    <!-- Features Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div
                            v-for="feature in features"
                            :key="feature.title"
                            class="rs-card p-8 hover:shadow-2xl transition-all duration-300 hover:scale-105"
                        >
                            <!-- Icon -->
                            <div class="rs-skill-icon mb-4">
                                <component :is="feature.icon" class="size-6 text-primary" />
                            </div>

                            <!-- Content -->
                            <h3 class="text-2xl font-bold mb-3" style="font-family: 'Cinzel', serif;">
                                {{ feature.title }}
                            </h3>
                            <p class="text-muted-foreground leading-relaxed">
                                {{ feature.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Learning Resources Section -->
        <section class="py-20 bg-card/30">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <!-- Section Header -->
                    <div class="text-center mb-16">
                        <h2 class="text-4xl md:text-5xl font-bold mb-4 rs-heading">
                            Curated Learning Resources
                        </h2>
                        <p class="text-xl text-muted-foreground max-w-2xl mx-auto">
                            Every skill comes with handpicked resources to accelerate your learning
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                        <!-- Visual Example -->
                        <div class="order-2 lg:order-1">
                            <div class="rs-panel">
                                <div class="space-y-3">
                                    <!-- Example Resource 1 -->
                                    <div class="flex items-center gap-3 rounded-lg border border-sidebar-border/50 p-4 bg-accent/5">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-500/10 text-red-600">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-semibold text-sm sm:text-base">JavaScript Mastery Course</div>
                                            <div class="text-muted-foreground text-xs">YouTube Tutorial</div>
                                        </div>
                                        <svg class="h-4 w-4 text-muted-foreground shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </div>

                                    <!-- Example Resource 2 -->
                                    <div class="flex items-center gap-3 rounded-lg border border-sidebar-border/50 p-4 bg-accent/5">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-500/10 text-blue-600">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-semibold text-sm sm:text-base">MDN Web Docs</div>
                                            <div class="text-muted-foreground text-xs">Documentation</div>
                                        </div>
                                        <svg class="h-4 w-4 text-muted-foreground shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </div>

                                    <!-- Example Resource 3 -->
                                    <div class="flex items-center gap-3 rounded-lg border border-sidebar-border/50 p-4 bg-accent/5">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-green-500/10 text-green-600">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-semibold text-sm sm:text-base">Practice Coding Challenges</div>
                                            <div class="text-muted-foreground text-xs">Interactive App</div>
                                        </div>
                                        <svg class="h-4 w-4 text-muted-foreground shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Text Content -->
                        <div class="order-1 lg:order-2">
                            <div class="space-y-6">
                                <div class="flex items-start gap-4">
                                    <div class="rs-skill-icon shrink-0">
                                        <svg class="size-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-2" style="font-family: 'Cinzel', serif;">
                                            Hand-Picked Quality
                                        </h3>
                                        <p class="text-muted-foreground">
                                            Each skill includes carefully selected videos, articles, apps, and websites to guide your learning journey.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div class="rs-skill-icon shrink-0">
                                        <svg class="size-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-2" style="font-family: 'Cinzel', serif;">
                                            Learn Faster
                                        </h3>
                                        <p class="text-muted-foreground">
                                            No more endless searching for tutorials. We've done the research so you can focus on learning and leveling up.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div class="rs-skill-icon shrink-0">
                                        <svg class="size-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-2" style="font-family: 'Cinzel', serif;">
                                            Always Available
                                        </h3>
                                        <p class="text-muted-foreground">
                                            Access your learning resources anytime, right from your skill page. Your path to mastery is always at your fingertips.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="py-20 bg-gradient-to-b from-card/30 to-background">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <div class="rs-panel text-center py-16 px-8">
                        <!-- Crown Icon -->
                        <div class="mb-6">
                            <Trophy class="size-16 mx-auto text-primary" />
                        </div>

                        <h2 class="text-4xl md:text-5xl font-bold mb-6 rs-heading">
                            Ready to Become Legendary?
                        </h2>
                        <p class="text-xl text-muted-foreground mb-8 max-w-2xl mx-auto">
                            Your journey to mastery starts today. Track your skills, gain experience,
                            and level up your life. The only question is: what will you master first?
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <Link
                                v-if="!$page.props.auth.user && canRegister"
                                :href="register()"
                                class="rs-button bg-primary border-primary text-primary-foreground text-xl px-10 py-4"
                            >
                                <Sword class="inline-block size-6 mr-2" />
                                Start Your Adventure
                            </Link>
                            <Link
                                v-else-if="$page.props.auth.user"
                                :href="dashboard()"
                                class="rs-button bg-primary border-primary text-primary-foreground text-xl px-10 py-4"
                            >
                                <Sword class="inline-block size-6 mr-2" />
                                Go to Dashboard
                            </Link>
                            <Link
                                v-else
                                :href="login()"
                                class="rs-button bg-secondary border-secondary text-secondary-foreground text-xl px-10 py-4"
                            >
                                Log In
                            </Link>
                        </div>

                        <!-- Tagline -->
                        <p class="mt-8 text-sm text-muted-foreground italic">
                            "The difference between who you are and who you want to be is what you do."
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t-2 border-border/50 py-8 bg-card/30">
            <div class="container mx-auto px-4">
                <div class="text-center text-muted-foreground text-sm">
                    <p class="mb-2">
                        Made with <span class="text-destructive">❤</span> by Michael Curtis
                    </p>
                    <p class="font-semibold rs-gold-text" style="font-family: 'Cinzel', serif;">
                        SKILLSCAPE - Master Your Journey
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
