<script setup>
import InputError from '@/Components/Forms/InputError.vue';
import InputLabel from '@/Components/Forms/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import FileInput from '@/Components/Forms/FileInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps(['video']);

const form = useForm({
    id: props.video.id,
    thumbnail: null
});

const user = usePage().props.auth.user;

const profilePictureInput = ref(null)

const updateThumbnail = () => {
    form.post(route('videos.updateThumbnail'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            newPicture.value = null
            profilePictureInput.value.value=''
        }
    });
};

const newPicture = ref('')

const setPicture = (file) => {
    // check file type and tell user if it is not an image
    if(!file.type.startsWith('image')) {
        newPicture.value = null
        form.thumbnail = null
        document.getElementById('profilePictureInput').value=''

        form.errors.picture = 'File is not an image.'

        return
    }

    form.errors.picture = ''

    form.thumbnail = file
    newPicture.value = URL.createObjectURL(file)

    console.log(form.thumbnail)
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Change thumbnail - {{ video.title }}</h2>

            <p class="mt-1 text-sm text-gray-600">
                Change the video's thumbnail.
            </p>
        </header>

        <form @submit.prevent="updateThumbnail" class="mt-6 space-y-6">
            <div>
                <div class="inline" v-if="user.profile_picture">
                    Current thumbnail
                    <img :src="video.thumbnail_asset" class="aspect-video bg-black object-contain rounded-sm h-60">
                </div>
                <div class="ml-4 inline" v-if="newPicture">
                    New thumbnail
                    <img :src="newPicture" class="aspect-video bg-black object-contain rounded-sm h-60">
                </div>
                
                <InputLabel for="profilePictureInput" value="Upload picture" />

                <FileInput
                    ref="profilePictureInput"
                    type="file"
                    class="mt-1 block"
                    @setFile="setPicture"
                    :accept="'image/*'"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.picture" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Update Thumbnail</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
