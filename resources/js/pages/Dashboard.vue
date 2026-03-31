<script setup lang="js">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

defineProps({
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
        <div class="mx-auto min-h-screen max-w-4xl p-6 text-slate-200">
            <div
                v-if="auth.user.data.is_admin"
                class="mb-12 rounded-2xl border border-slate-800 bg-slate-900/50 p-8 shadow-2xl backdrop-blur-sm"
            >
                <div class="mb-6 flex items-center gap-3">
                    <div class="h-8 w-1 rounded-full bg-indigo-500"></div>
                    <h2 class="text-2xl font-bold text-white">
                        Nuovo Workshop
                    </h2>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="col-span-2">
                            <label
                                class="mb-2 block text-sm font-medium text-slate-400"
                                >Titolo del Workshop</label
                            >
                            <input
                                v-model="form.title"
                                type="text"
                                class="w-full rounded-lg border-slate-700 bg-slate-800 text-white placeholder-slate-500 transition focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Es: Architetture AI con Laravel"
                            />
                        </div>

                        <div class="col-span-2">
                            <div class="mb-2 flex items-center justify-between">
                                <label
                                    class="block text-sm font-medium text-slate-400"
                                    >Descrizione</label
                                >
                                <button
                                    type="button"
                                    @click="suggestDescription"
                                    :disabled="isGenerating"
                                    class="flex items-center gap-2 text-xs font-bold tracking-wider text-indigo-400 uppercase transition hover:text-indigo-300 disabled:opacity-50"
                                >
                                    <span
                                        v-if="isGenerating"
                                        class="animate-pulse"
                                        >⚡ Elaborazione...</span
                                    >
                                    <span v-else>✨ Genera con AI</span>
                                </button>
                            </div>
                            <textarea
                                v-model="form.description"
                                rows="4"
                                class="w-full rounded-lg border-slate-700 bg-slate-800 text-white transition focus:border-indigo-500 focus:ring-indigo-500"
                            ></textarea>
                        </div>

                        <div>
                            <label
                                class="mb-2 block text-sm font-medium text-slate-400"
                                >Data e Ora</label
                            >
                            <input
                                v-model="form.scheduled_at"
                                type="datetime-local"
                                class="w-full rounded-lg border-slate-700 bg-slate-800 text-white focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>
                        <div>
                            <label
                                class="mb-2 block text-sm font-medium text-slate-400"
                                >Posti Totali</label
                            >
                            <input
                                v-model="form.capacity"
                                type="number"
                                class="w-full rounded-lg border-slate-700 bg-slate-800 text-white focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full rounded-lg bg-indigo-600 py-3 font-bold text-white shadow-lg shadow-indigo-900/20 transition-all hover:bg-indigo-500 active:scale-[0.98]"
                    >
                        Pubblica Workshop
                    </button>
                </form>
            </div>

            <div
                class="mb-8 flex items-end justify-between border-b border-slate-800 pb-4"
            >
                <h1 class="text-3xl font-extrabold tracking-tight text-white">
                    Workshop <span class="text-indigo-500">Disponibili</span>
                </h1>
                <span class="text-sm text-slate-500"
                    >{{ workshops.data.length }} eventi trovati</span
                >
            </div>

            <div class="grid gap-4">
                <div
                    v-for="workshop in workshops.data"
                    :key="workshop.id"
                    class="group relative flex items-center justify-between rounded-xl border border-slate-800 bg-slate-900 p-6 shadow-sm transition-all duration-300 hover:border-slate-700 hover:bg-slate-800/50"
                >
                    <div class="max-w-[70%]">
                        <div class="mb-2 flex items-center gap-2">
                            <span
                                class="rounded border border-indigo-500/20 bg-indigo-500/10 px-2 py-0.5 text-[10px] font-bold text-indigo-400 uppercase"
                                >Workshop</span
                            >
                            <h2
                                class="text-xl font-bold text-slate-100 group-hover:text-white"
                            >
                                {{ workshop.title }}
                            </h2>
                        </div>
                        <p
                            class="mb-4 line-clamp-2 text-sm leading-relaxed text-slate-400"
                        >
                            {{ workshop.description }}
                        </p>

                        <div
                            class="flex items-center gap-4 text-xs font-semibold text-slate-500"
                        >
                            <span class="flex items-center gap-1"
                                >📅 {{ workshop.date_display }}</span
                            >
                            <span class="flex items-center gap-1"
                                >👥 {{ workshop.available_seats }} /
                                {{ workshop.capacity }} posti</span
                            >
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-3">
                        <Link
                            v-if="!workshop.is_registered"
                            :href="`/workshops/${workshop.id}/register`"
                            method="post"
                            as="button"
                            class="rounded-lg bg-white px-6 py-2 text-sm font-bold text-slate-900 shadow-xl transition-colors hover:bg-indigo-400"
                        >
                            Iscriviti
                        </Link>

                        <Link
                            v-else
                            :href="`/workshops/${workshop.id}/unregister`"
                            method="delete"
                            as="button"
                            class="rounded-lg border border-red-500/30 px-6 py-2 text-sm font-bold text-red-400 transition-all hover:bg-red-500/10"
                        >
                            Annulla
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
