<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/Forms/InputError.vue';
import InputLabel from '@/Components/Forms/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
import SelectInput from '@/Components/Forms/SelectInput.vue';
import TextAreaInput from '@/Components/Forms/TextAreaInput.vue';
import FormContainer from '@/Components/Forms/FormContainer.vue';
import UpdateVideoThumbnail from './Partials/UpdateVideoThumbnail.vue'

import { useForm, Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps(['video'])

var visibility = 'Public'

if(props.video.visibility == 1) {
    visibility = 'Unlisted'
} else if(props.video.visibility == 2) {
    visibility = 'Private'
}

const form = useForm({
    id: props.video.id,
    title: props.video.title,
    description: props.video.description,
    visibility: visibility,
});

const updateVideo = () => {
    form.post(route('videos.update', props.video.url_token), {
        preserveScroll: true,
        onFinish: () => form.reset()
    });
}

const deleteVideo = () => {
    const form = useForm({
        id: props.video.id
    })

    form.delete(route('videos.destroy', props.video.url_token), [props.video.id, 1])

    closeModal()
}

const openModal = () => {
    showModal.value = true
}

const showModal = ref(false)

const closeModal = () => {
    showModal.value = false
}

const options = ["public", "unlisted", "private"];
</script>

<template>
    <Head :title="'Edit video ' + video.title"/>
    
    <AuthenticatedLayout :disableSidebar="true">
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <FormContainer>
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <header class="mb-8">
                        <h2 class="text-lg font-medium text-gray-900">Edit Video - {{ video.title }}</h2>

                        <p class="mt-1 text-sm text-gray-600">
                            Change video properties.
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
                    <SelectInput class="max-w-xl mb-4" required v-model="visibility" :options="options"/>
                    <InputError class="mt-2" :message="form.errors.visibility" />

                    <div class="block"></div>
                    
                    <PrimaryButton class="max-w-xl mt-4" @click="updateVideo">Update Video</PrimaryButton>
                </form>
            </FormContainer>

            <Modal :show="showModal" @close="closeModal">
                <div class="flex flex-col">
                    <div class="mb-6">
                        <span class="text-xl">Are you sure you want to do delete the video </span>
                        <span class="text-2xl text-red-800">{{ video.title }}</span>?
                        <p class="text-sm">This action is permanent.</p>
                    </div>
                    <div class="flex">
                        <PrimaryButton @click="deleteVideo" class="bg-red-800">Delete</PrimaryButton>
                        <div class="grow"></div>
                        <PrimaryButton @click="closeModal">Cancel</PrimaryButton>
                    </div>
                </div>
            </Modal>

            <FormContainer>
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <header class="mb-8">
                        <h2 class="text-lg font-medium text-gray-900">Delete video - {{ video.title }}</h2>

                        <p class="mt-1 text-sm text-gray-600">
                            Delete this video permanently.
                        </p>
                    </header>
                    <PrimaryButton class="max-w-xl mt-4 bg-red-800" @click="openModal">Delete Video</PrimaryButton>
                </form>
            </FormContainer>

            <FormContainer>
                   <UpdateVideoThumbnail :video="video"/>
            </FormContainer>
        </div>
    </AuthenticatedLayout>
</template>
