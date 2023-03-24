<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DropdownMenu from '@/Components/DropdownMenu.vue';
import Modal from '@/Components/Modal.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps(['playlists'])

console.log(props.playlists)

const main = ref(null)

const showModal = ref(false)

const playlistName = ref(null)
const playlistToDelete = ref(null)

const openDeletePlaylistModal = (playlist) => {
    playlistToDelete.value = playlist
    console.log(playlist)
    showModal.value = true

    playlistName.value = playlist.title
}

const deletePlaylist = () => {
    const form = useForm({
        playlistId: playlistToDelete.value.id
    })

    form.delete(route('playlists.destroy'), [playlistToDelete.value.id, 1])

    closeModal()
}

const closeModal = () => {
    showModal.value = false
}

/* onMounted(() => {
    deletePlaylistButton.value.addEventListener('click', )
}) */
</script>

<template>
    <Head title="Homepage"/>
    <Modal :show="showModal" @close="closeModal">
        <div class="flex flex-col">
            <div class="mb-6">
                <span class="text-xl">Are you sure you want to do delete the playlist </span>
                <span class="text-2xl text-red-800">{{ playlistName }}</span>?
                <p class="text-sm">This action is permanent.</p>
            </div>
            <div class="flex">
                <PrimaryButton @click="deletePlaylist" class="bg-red-800">Delete</PrimaryButton>
                <div class="grow"></div>
                <PrimaryButton @click="closeModal">Cancel</PrimaryButton>
            </div>
        </div>
    </Modal>
    <AuthenticatedLayout :main="main">
        <div ref="main" class="grid grid-flow-row grid-rows-max gap-4 grid-cols-1 text-left text-xl pl-24 p-10">
            <!-- Create playlist button -->
            <Link href="/playlists/create">
                <div class="p-2 bg-white border-2 text-center font-bold tracking-wide">
                    <svg stroke="black" class="top-1/2 w-7 h-7 inline" viewBox="0 0 64 64"><path stroke-linecap="square" d="M32 0 L 32 64 M0 32 L 64 32" stroke-width="10"/></svg>
                    <span class="ml-2">Create a new playlist</span>
                </div>
            </Link>
            
            <!-- playlists -->
            <div 
                class="w-full border-2 p-2 flex bg-white"
                v-for="playlist in playlists"
                key="playlist.id"
                :playlist="playlist"
            >
                <!-- thumbnail -->
                <Link :href="'/playlists/'+playlist.url_token">
                    <img class="object-contain aspect-video h-32 bg-black" :src="playlist.thumbnail">
                </Link>

                <!-- info -->
                <div class="p-2">
                    <Link :href="'/playlists/'+playlist.url_token">
                        <span class="block">{{ playlist.title }}</span>
                    </Link>

                    <span>Videos in playlist: {{ playlist.length }}</span>
                </div>

                <div class="grow"></div>

                <DropdownMenu>
                    <div @click="openDeletePlaylistModal(playlist)" class="hover:cursor-pointer hover:bg-gray-300 p-2">
                        Delete playlist
                    </div>
                </DropdownMenu>
            </div>
        </div>
    </AuthenticatedLayout>
</template>