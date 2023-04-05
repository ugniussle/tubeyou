<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '../Modal.vue';
import PrimaryButton from '../PrimaryButton.vue';

const props = defineProps(['playlist'])

const showModal = ref(false)

const openDeletePlaylistModal = () => {
    console.log(showModal.value)
    showModal.value = true
    console.log(showModal.value)
}

const deletePlaylist = () => {
    const form = useForm({
        playlistId: props.playlist.id
    })

    form.delete(route('playlists.destroy'), [props.playlist.id, 1])

    closeModal()
}

const closeModal = () => {
    showModal.value = false
}
</script>

<template>
    <div @click="openDeletePlaylistModal" class="hover:cursor-pointer hover:bg-gray-300 p-2">
        Delete playlist
    </div>

    <Modal v-if="showModal" :show="showModal" @close="closeModal">
        <div class="flex flex-col">
            <div class="mb-6">
                <span class="text-xl">Are you sure you want to do delete the playlist </span>
                <span class="text-2xl text-red-800">{{ props.playlist.title }}</span>?
                <p class="text-sm">This action is permanent.</p>
            </div>
            <div class="flex">
                <PrimaryButton @click="deletePlaylist" class="bg-red-800">Delete</PrimaryButton>
                <div class="grow"></div>
                <PrimaryButton @click="closeModal">Cancel</PrimaryButton>
            </div>
        </div>
    </Modal>
</template>