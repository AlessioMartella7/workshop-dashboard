<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

// Recuperiamo i dati globali che abbiamo configurato nel middleware HandleInertiaRequests
const page = usePage();
const user = computed(() => page.props.auth.user);
const flash = computed(() => page.props.flash);
console.log(user);
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="flex items-center justify-between border-b bg-white p-4">
            <div>
                <strong class="text-lg">Academy Workshop</strong>
                <span class="ml-4 text-sm text-gray-500"
                    >Ciao, {{ user.data.name }} ({{ user.data.role }})</span
                >
            </div>

            <div class="space-x-4">
                <Link href="/dashboard" class="hover:text-blue-400"
                    >Dashboard</Link
                >
                <Link
                    href="/logout"
                    method="post"
                    as="button"
                    class="text-red-600"
                    >Logout</Link
                >
            </div>
        </nav>

        <div
            v-if="flash.success"
            class="bg-green-100 p-4 text-center text-green-800"
        >
            {{ flash.success }}
        </div>
        <div v-if="flash.error" class="bg-red-100 p-4 text-center text-red-800">
            {{ flash.error }}
        </div>

        <main class="p-6">
            <slot />
        </main>
    </div>
</template>
