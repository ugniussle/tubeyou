<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import FileInput from "@/Components/FileInput.vue"
import FileUpload from '@/Components/FileUpload.vue';

import { useForm } from '@inertiajs/vue3';

const props = defineProps(['csrf_token'])

const form = useForm({
    title: '',
    description: '',
    visibility: 'Public',
    filename: '',
});

const createVideo = () => {
    form.post(route('videos.store'), {
        preserveScroll: true,
        // onFinish: () => form.reset(),
    });
};

const options = ["public", "unlisted", "private"];
</script>

<template>
    <Head title="Upload video"/>
    <AuthenticatedLayout>
        <div class="p-10 text-center">
            <form @submit.prevent="submit" enctype="multipart/form-data">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">Upload Video</h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Upload a video for the whole world to see. Or not.
                    </p>
                </header>

                <InputError class="mt-2" :message="form.errors.title" />

                <InputLabel>Title</InputLabel>
                <TextInput required v-model="form.title" type="text"></TextInput>

                <InputError class="mt-2" :message="form.errors.description" />

                <InputLabel>Description</InputLabel>
                <TextAreaInput v-model="form.description"></TextAreaInput>

                <InputError class="mt-2" :message="form.errors.filename" />
                <InputLabel>Video file</InputLabel>
                <FileUpload :csrf_token="csrf_token" v-model="form.filename"></FileUpload>

                <InputError class="mt-2" :message="form.errors.visibility" />
                <InputLabel>Visibility</InputLabel>
                <SelectInput required v-model="form.visibility" :options="options">
                </SelectInput>

                <div>
                    <PrimaryButton class="mt-4" @click="createVideo">Create</PrimaryButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>