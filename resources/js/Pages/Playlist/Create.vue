<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/Forms/InputError.vue';
import InputLabel from '@/Components/Forms/InputLabel.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
import SelectInput from '@/Components/Forms/SelectInput.vue'
import FormContainer from '@/Components/Forms/FormContainer.vue';

import { useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    visibility: 'Public',
});

const createPlaylist = () => {
    form.post(route('playlists.store'), {
        preserveScroll: true,
        // onFinish: () => form.reset(),
    });
};

const options = ["public", "unlisted", "private"];
</script>

<template>
    <Head title="Create playlist"/>
    
    <AuthenticatedLayout :hideSidebar="true">
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <FormContainer>
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <header class="mb-8">
                        <h2 class="text-lg font-medium text-gray-900">Create a playlist</h2>

                        <p class="mt-1 text-sm text-gray-600">
                            Create a playlist to keep all your favorite videos in one place.
                        </p>
                    </header>

                    <!-- playlist title -->
                    <InputLabel>Title</InputLabel>
                    <TextInput class="max-w-xl mb-4" required v-model="form.title" type="text"></TextInput>
                    <InputError class="mt-2" :message="form.errors.title" />

                    <!-- playlist visibility -->
                    <InputLabel>Visibility</InputLabel>
                    <SelectInput class="block max-w-xl mb-4" required v-model="form.visibility" :options="options"/>
                    <InputError class="mt-2" :message="form.errors.visibility" />

                    <PrimaryButton class="mt-4" @click="createPlaylist">Create playlist</PrimaryButton>
                </form>
            </FormContainer>
        </div>
    </AuthenticatedLayout>
</template>
