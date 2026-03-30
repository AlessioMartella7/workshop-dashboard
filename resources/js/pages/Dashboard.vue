<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    workshops: Object,
    auth: Object,
});

console.log(props);

const form = useForm({
    title: '',
    description: '',
    scheduled_at: '',
    capacity: 20,
});
</script>

<template>
    <Head title="Workshop List" />

    <AppLayout>
        <div class="mx-auto max-w-4xl">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Workshop Disponibili</h1>

                <button
                    v-if="auth.user.data.is_admin"
                    @click="showModal = true"
                    class="rounded bg-green-600 px-4 py-2 text-white shadow hover:bg-green-700"
                >
                    + Nuovo Workshop
                </button>
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
