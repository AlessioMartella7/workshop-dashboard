<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

// Recuperiamo i dati globali in modo reattivo
const page = usePage();
const user = computed(() => page.props.auth.user);
const flash = computed(() => page.props.flash);
</script>

<template>
    <div class="min-h-screen bg-slate-950 font-sans antialiased">
        <nav
            class="sticky top-0 z-50 flex items-center justify-between border-b border-slate-800 bg-slate-900/50 p-4 backdrop-blur-md"
        >
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded bg-indigo-600 shadow-lg shadow-indigo-500/20"
                    >
                        <span class="font-bold text-white">A</span>
                    </div>
                    <strong class="text-lg tracking-tight text-white italic"
                        >Academy<span class="text-indigo-500"
                            >Workshop</span
                        ></strong
                    >
                </div>

                <span
                    class="ml-4 hidden border-l border-slate-800 pl-4 text-xs font-medium text-slate-500 sm:inline-block"
                >
                    Bentornato,
                    <span class="text-slate-300">{{ user.data.name }}</span>
                    <span
                        class="ml-1 rounded bg-slate-800 px-1.5 py-0.5 text-[10px] tracking-tighter uppercase"
                        >{{ user.data.role }}</span
                    >
                </span>
            </div>

            <div class="flex items-center gap-6 text-sm font-semibold">
                <Link
                    href="/dashboard"
                    class="text-slate-400 transition-colors hover:text-white"
                >
                    Dashboard
                </Link>

                <Link
                    href="/logout"
                    method="post"
                    as="button"
                    class="rounded-lg border border-red-500/20 bg-red-500/10 px-3 py-1.5 text-red-400 transition-all duration-200 hover:bg-red-500 hover:text-white"
                >
                    Logout
                </Link>
            </div>
        </nav>

        <div class="fixed top-20 right-6 z-50 flex max-w-md flex-col gap-2">
            <Transition
                enter-active-class="transform transition duration-300 ease-out"
                enter-from-class="translate-x-full opacity-0"
                enter-to-class="translate-x-0 opacity-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="flash.success"
                    class="rounded-lg border border-emerald-500/30 bg-emerald-500/10 p-4 text-emerald-400 shadow-xl backdrop-blur-md"
                >
                    <div class="flex items-center gap-3">
                        <span class="text-lg">✅</span>
                        <p class="text-sm font-medium">{{ flash.success }}</p>
                    </div>
                </div>
            </Transition>

            <Transition
                enter-active-class="transform transition duration-300 ease-out"
                enter-from-class="translate-x-full opacity-0"
                enter-to-class="translate-x-0 opacity-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="flash.error"
                    class="rounded-lg border border-red-500/30 bg-red-500/10 p-4 text-red-400 shadow-xl backdrop-blur-md"
                >
                    <div class="flex items-center gap-3">
                        <span class="text-lg">❌</span>
                        <p class="text-sm font-medium">{{ flash.error }}</p>
                    </div>
                </div>
            </Transition>
        </div>

        <main class="mx-auto max-w-7xl p-6">
            <slot />
        </main>

        <footer class="p-10 text-center">
            <p class="text-[10px] tracking-[0.2em] text-slate-700 uppercase">
                System Status: Online • Powered by Laravel & AI
            </p>
        </footer>
    </div>
</template>
