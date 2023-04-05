<script setup>
import { ref } from 'vue';
import PlaylistModal from '@/Components/PlaylistModal.vue';

const props = defineProps(['video'])

const selectedVideoId = ref(null)
const modalOpen = ref(false)

const openPlaylistModal = async (videoId) => {
    modalOpen.value = true
    selectedVideoId.value = videoId
}

const closePlaylistModal = () => {
    modalOpen.value = false
}

</script>

<template>
    <div @click="openPlaylistModal(video.id)" class="hover:cursor-pointer hover:bg-gray-300 p-2">
        Add to playlist...
    </div>

    <PlaylistModal
        v-if="modalOpen"
        :selectedVideoId="video.id" 
        :show="modalOpen" 
        @close="closePlaylistModal"
    />
</template>