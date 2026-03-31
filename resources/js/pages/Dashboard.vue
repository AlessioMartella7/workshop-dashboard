<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    workshops: Object,
    auth: Object,
});

// Stato per il caricamento dell'AI
const isGenerating = ref(false);

// Form per la creazione del workshop
const form = useForm({
    title: '',
    description: '',
    scheduled_at: '',
    capacity: 20,
});

// Funzione per chiamare il controller AI
const suggestDescription = async () => {
    if (!form.title) {
        alert('Inserisci un titolo prima di generare la descrizione!');
        return;
    }

    isGenerating.value = true;
    try {
        const response = await axios.post('/admin/generate-description', {
            title: form.title,
        });
        form.description = response.data.description;
    } catch (error) {
        console.error('Errore AI:', error);
        alert('Impossibile generare la descrizione. Controlla la console.');
    } finally {
        isGenerating.value = false;
    }
};

// Invio del form al backend
const submit = () => {
    form.post('/admin/workshops', {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Workshop Dashboard" />

    <AppLayout>
        <div class="mx-auto max-w-4xl text-black">
            <div
                v-if="auth.user.data.is_admin"
                class="mb-10 rounded-xl border border-blue-200 bg-blue-50 p-6 shadow-sm"
            >
                <h2 class="mb-4 text-xl font-bold text-blue-800">
                    Crea Nuovo Workshop
                </h2>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Titolo</label
                        >
                        <input
                            v-model="form.title"
                            type="text"
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Esempio: Corso Avanzato Laravel"
                        />
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Descrizione</label
                            >
                            <button
                                type="button"
                                @click="suggestDescription"
                                :disabled="isGenerating"
                                class="text-xs font-semibold text-purple-600 hover:text-purple-800 disabled:opacity-50"
                            >
                                {{
                                    isGenerating
                                        ? '✨ Sto scrivendo...'
                                        : '✨ Suggerisci con AI'
                                }}
                            </button>
                        </div>
                        <textarea
                            v-model="form.description"
                            rows="3"
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        ></textarea>
                        <div
                            v-if="form.errors.description"
                            class="text-xs text-red-600"
                        >
                            {{ form.errors.description }}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Data e Ora</label
                            >
                            <input
                                v-model="form.scheduled_at"
                                type="datetime-local"
                                class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Capacità</label
                            >
                            <input
                                v-model="form.capacity"
                                type="number"
                                class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full rounded-md bg-blue-600 py-2 font-semibold text-white hover:bg-blue-700 disabled:bg-blue-400"
                    >
                        Salva Workshop
                    </button>
                </form>
            </div>

            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Workshop Disponibili</h1>
            </div>

            <div class="space-y-4">
                <div
                    v-for="workshop in workshops.data"
                    :key="workshop.id"
                    class="flex items-center justify-between rounded-lg bg-white p-4 shadow"
                >
                    <div class="max-w-[70%]">
                        <h2 class="text-lg font-bold">{{ workshop.title }}</h2>
                        <p class="mb-1 text-sm text-gray-500 italic">
                            {{ workshop.description }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ workshop.date_display }}
                        </p>
                        <p class="mt-1 text-xs text-gray-400">
                            Posti: {{ workshop.available_seats }} /
                            {{ workshop.capacity }}
                        </p>
                    </div>

                    <div>
                        <Link
                            v-if="!workshop.is_registered"
                            :href="`/workshops/${workshop.id}/register`"
                            method="post"
                            as="button"
                            class="rounded bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700"
                        >
                            Iscriviti
                        </Link>

                        <Link
                            v-else
                            :href="`/workshops/${workshop.id}/unregister`"
                            method="delete"
                            as="button"
                            class="rounded border border-red-600 px-4 py-2 text-red-600 transition hover:bg-red-50"
                        >
                            Annulla
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
