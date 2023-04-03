<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import VideoPreview from '@/Components/VideoPreviewGrid.vue';
import PlaylistPreview from '@/Components/PlaylistPreviewList.vue';
import PlaylistModal from '@/Components/PlaylistModal.vue';
import ProfilePicture from '@/Components/ProfilePicture.vue';
import SubscribeButton from '@/Components/SubscribeButton.vue';
import NavLink from '@/Components/NavLink.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps(['videos', 'playlists', 'channel'])

const main = ref(null)

const selectedVideoId = ref(null)
const modalOpen = ref(false)

const openPlaylistModal = async (videoId) => {
    modalOpen.value = true
    selectedVideoId.value = videoId
}

const closePlaylistModal = () => {
    modalOpen.value = false
}

const Menus = {
    VIDEOS: 0,
    PLAYLISTS: 1
}

const selectedMenu = ref(0)

</script>

<template>
    <Head :title="channel.username"/>
    <AuthenticatedLayout :main="main">
        <div ref="main">
            <div>
                <!-- channel -->
                <div class="flex pl-24 p-10 bg-white">
                    <ProfilePicture class="inline mr-6 flex-none" :user="channel" :size="'8rem'" />
                    <h2 class="mr-6 inline font-semibold text-xl text-gray-800 leading-tight my-auto">{{ channel.username + '\'s Channel' }}</h2>
                    <SubscribeButton :channel="channel" class="w-40 my-auto flex-none text-center"/>
                </div>

                <!-- menu -->
                <div class="flex pl-24 h-12 bg-gray-200 space-x-10">
                    <NavLink 
                        @click="selectedMenu = Menus.VIDEOS" 
                        :active="selectedMenu == Menus.VIDEOS"
                    >
                        Videos
                    </NavLink>

                    <NavLink 
                        @click="selectedMenu = Menus.PLAYLISTS" 
                        :active="selectedMenu == Menus.PLAYLISTS"
                    >
                        Playlists
                    </NavLink>
                </div>

                <!-- videos -->
                <div v-show="selectedMenu == Menus.VIDEOS" class="grid grid-flow-row grid-rows-max gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 text-center text-xl pl-24 p-10">
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

                <!-- playlists -->
                <div v-if="selectedMenu == Menus.PLAYLISTS" class="grid grid-flow-row grid-rows-max gap-4 grid-cols-1 text-left text-xl pl-24 p-10">
                    <PlaylistPreview
                        v-for="playlist in playlists"
                        :key="playlist.id"
                        :playlist="playlist"
                    >

                    </PlaylistPreview>
                </div>
            </div>

            <div>

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