<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import VideoPreview from '@/Components/VideoPreview.vue';
import PlaylistModal from '@/Components/PlaylistModal.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps(['videos'])

const main = ref(null)

const selectedVideoId = ref(null)
const modalOpen = ref(false)

const openPlaylistModal = async (videoId) => {
    console.log('opening playlist modal', videoId)

    modalOpen.value = true
    selectedVideoId.value = videoId
}

const closePlaylistModal = () => {
    console.log('closing playlist modal')

    modalOpen.value = false
}

</script>

<template>
    <Head title="Homepage"/>
    <AuthenticatedLayout :main="main">
        <div 
            class="grid grid-flow-row grid-rows-max gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 text-center text-xl pl-24 p-10"
            ref="main"
        >
            <VideoPreview 
                v-for="video in videos"
                :key="video.id"
                :video="video"
                v-on:open-playlist-modal="($id) => openPlaylistModal($id)"
            />
        </div>
    </AuthenticatedLayout>

    <PlaylistModal
        v-if="modalOpen"
        :selectedVideoId="selectedVideoId" 
        :show="modalOpen" 
        @close="closePlaylistModal"
    />
</template>