<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { Sword, Target, TrendingUp, Trophy, Zap, Shield, Menu, X } from 'lucide-vue-next';
import { ref } from 'vue';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const mobileMenuOpen = ref(false);

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
                        Made with <span class="text-destructive">❤</span> for those who level up
                    </p>
                    <p class="font-semibold rs-gold-text" style="font-family: 'Cinzel', serif;">
                        SKILLSCAPE - Master Your Journey
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
