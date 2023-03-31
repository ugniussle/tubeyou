<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import VideoPreview from '@/Components/VideoPreviewGrid.vue';
import PlaylistModal from '@/Components/PlaylistModal.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import ProfilePicture from '@/Components/ProfilePicture.vue';
import SubscribeButton from '@/Components/SubscribeButton.vue';

defineProps(['videos', 'channel'])

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
    <Head :title="channel.username"/>
    <AuthenticatedLayout :main="main">
        <div ref="main">
            <div class="flex pl-24 p-10 bg-white">
                <ProfilePicture class="inline mr-6 flex-none" :user="channel" :size="'8rem'" />
                <h2 class="mr-6 inline font-semibold text-xl text-gray-800 leading-tight my-auto">{{ channel.username + '\'s videos' }}</h2>
                <SubscribeButton :channel="channel" class="w-40 my-auto flex-none text-center"/>
            </div>
            
            <div class="grid grid-flow-row grid-rows-max gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 text-center text-xl pl-24 p-10">
                <VideoPreview 
                    v-for="video in videos"
                    :key="video.id"
                    :video="video"
                >
                    <!-- dropdown menu items -->
                    <div @click="openPlaylistModal(video.id)" class="hover:cursor-pointer hover:bg-gray-300 p-2">
                        Add to playlist...
                    </div>
                </VideoPreview>
            </div>
        </div>
    </AuthenticatedLayout>

    <PlaylistModal
        v-if="modalOpen"
        :selectedVideoId="selectedVideoId" 
        :show="modalOpen" 
        @close="closePlaylistModal"
    />
</template>