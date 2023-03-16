<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import FileInput from '@/Components/FileInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import ProfilePicture from '@/Components/ProfilePicture.vue';

const props = defineProps({
    status: String,
});

const form = useForm({
    picture: null,
});

const user = usePage().props.auth.user;

const updatePicture = () => {
    form.post(route('profile.updatePicture'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            newPicture.value = null
            document.getElementById('profilePictureInput').value=''
        }
    });
};

const newPicture = ref("")

const setPicture = (file) => {
    // check file type and tell user if it is not an image
    if(!file.type.startsWith('image')) {
        console.log('bad file')
        newPicture.value = null
        form.picture = null
        document.getElementById('profilePictureInput').value=''

        form.errors.picture = 'File is not an image.'

        return
    }

    form.errors.picture = ''

    form.picture = file
    newPicture.value = URL.createObjectURL(file)

/*     console.log(document.getElementById('profilePictureInput'))
    console.log(newPicture.value) */
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Picture</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile picture.
            </p>
        </header>

        <form @submit.prevent="updatePicture" class="mt-6 space-y-6">
            <div>
                <div class="inline" v-if="user.profile_picture">
                    Current profile picture
                    <ProfilePicture class="inline" :picture="user.profile_picture" :size="20"/>
                </div>
                <div class="ml-4 inline" v-if="newPicture">
                    New profile picture
                    <ProfilePicture class="inline" :picture="newPicture" :size="20"/>
                </div>
                <InputLabel for="profilePictureInput" value="Upload picture" />

                <FileInput
                    id="profilePictureInput"
                    type="file"
                    class="mt-1 block"
                    @setFile="setPicture"
                    accept="image/*"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.picture" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
