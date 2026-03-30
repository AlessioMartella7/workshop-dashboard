<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

// Riceviamo i workshop dalla WorkshopResource che abbiamo configurato nel controller
defineProps({
    workshops: Object,
});
</script>

<template>
    <Head title="Workshop List" />

    <AppLayout>
        <div class="mx-auto max-w-4xl">
            <h1 class="mb-6 text-2xl font-bold">Workshop Disponibili</h1>

            <div class="mb-4 bg-yellow-200 p-4 text-black">
                DEBUG: Ci sono {{ workshops.data.length }} workshop caricati.
            </div>

            <div class="space-y-4">
                <div
                    v-for="workshop in workshops.data"
                    :key="workshop.id"
                    class="flex items-center justify-between rounded-lg bg-white p-4 shadow"
                >
                    <div>
                        <h2 class="text-lg font-bold">{{ workshop.title }}</h2>
                        <p class="text-sm text-gray-600">
                            {{ workshop.date_display }}
                        </p>
                        <p class="mt-1 text-xs">
                            Posti disponibili: {{ workshop.available_seats }} /
                            {{ workshop.capacity }}
                        </p>
                    </div>

                    <div>
                        <Link
                            v-if="!workshop.is_registered"
                            :href="`/workshops/${workshop.id}/register`"
                            method="post"
                            as="button"
                            class="rounded bg-blue-600 px-4 py-2 text-white"
                        >
                            Iscriviti
                        </Link>

                        <Link
                            v-else
                            :href="`/workshops/${workshop.id}/unregister`"
                            method="delete"
                            as="button"
                            class="rounded border border-red-600 px-4 py-2 text-red-600"
                        >
                            Annulla
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
