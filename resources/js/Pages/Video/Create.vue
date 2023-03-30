<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/Forms/InputError.vue';
import InputLabel from '@/Components/Forms/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
import SelectInput from '@/Components/Forms/SelectInput.vue';
import TextAreaInput from '@/Components/Forms/TextAreaInput.vue';
import FileUpload from '@/Components/Forms/FileUpload.vue';
import FormContainer from '@/Components/Forms/FormContainer.vue';

import { useForm } from '@inertiajs/vue3';

const props = defineProps(['csrfToken'])

const form = useForm({
    title: '',
    description: '',
    visibility: 'Public',
    filename: '',
});

const createVideo = () => {
    form.post(route('videos.store'), {
        preserveScroll: true,
        onFinish: () => form.reset()
    });
};

const options = ["public", "unlisted", "private"];
</script>

<template>
    <Head title="Upload video"/>
    
    <AuthenticatedLayout :disableSidebar="true">
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <FormContainer>
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <header class="mb-8">
                        <h2 class="text-lg font-medium text-gray-900">Upload Video</h2>

                        <p class="mt-1 text-sm text-gray-600">
                            Upload a video for the whole world to see. Or not.
                        </p>
                    </header>

                    <!-- video title -->
                    <InputLabel>Title</InputLabel>
                    <TextInput class="max-w-xl mb-4" required v-model="form.title" type="text"></TextInput>
                    <InputError class="mt-2" :message="form.errors.title" />

                    <!-- video description -->
                    <InputLabel>Description</InputLabel>
                    <TextAreaInput class="max-w-xl mb-4" v-model="form.description"></TextAreaInput>
                    <InputError class="mt-2" :message="form.errors.description" />

                    <!-- video visibility -->
                    <InputLabel>Visibility</InputLabel>
                    <SelectInput class="max-w-xl mb-4" required v-model="form.visibility" :options="options"/>
                    <InputError class="mt-2" :message="form.errors.visibility" />

                    <!-- video file -->
                    <InputLabel>Video file</InputLabel>
                    <FileUpload 
                        class="max-w-xl" 
                        :csrfToken="csrfToken" 
                        v-model="form.filename" 
                        @update-error-message="($message) => form.errors.filename = $message"
                    />
                    <InputError class="mt-2" :message="form.errors.filename" />
                    
                    <PrimaryButton class="mt-4" v-show="form.filename" @click="createVideo">Publish Video</PrimaryButton>
                </form>
            </FormContainer>
        </div>
    </AuthenticatedLayout>
</template>
