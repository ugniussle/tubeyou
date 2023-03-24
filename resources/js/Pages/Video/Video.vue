<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import ProfilePicture from '@/Components/ProfilePicture.vue';
import { ref } from 'vue';
import DropdownMenu from '@/Components/DropdownMenu.vue';
import PlaylistModal from '@/Components/PlaylistModal.vue';

const props = defineProps(['video', 'channel'])

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
    <Head :title="video.title"/>
    <AuthenticatedLayout :main="main" :hideSidebar="true">
        <div class="text-xl m-10" ref="main">
            <!-- video -->
            <video class="w-2/3 m-2 ml-0 aspect-video bg-black" controls width="320" height="240" autoplay>
                <source :src="video.video_asset">
                Your browser does not support the video tag.
            </video> 

            <!-- video info -->
            <div class="p-1 flex w-2/3">
                <ProfilePicture class="inline mr-2" :picture="channel.profile_picture" :size="'2.5rem'"/>
                
                <div class="inline">
                    <!-- title -->
                    <Link :href="route('videos.url_token', video.url_token)" class="block text-left">
                        {{ video.title }}
                    </Link>

                    <!-- channel name -->
                    <div class="text-left text-sm">
                        {{ channel.username }}  
                    </div>

                    <!-- view count and date -->
                    <div class="text-sm">
                        <span>{{ video.views }} views</span>
                        <svg class="inline w-5" viewBox="0 0 10 10">
                            <circle cx="5" cy="4" r="1"/>
                        </svg>
                        <span>{{ video.created_at.slice(0, 10) }} </span>
                        
                    </div>
                </div>

                <!-- spacer -->
                <div class="grow"/>

                <!-- dropdown with button -->
                <DropdownMenu>
                    <div @click="openPlaylistModal(video.id)" class="hover:cursor-pointer hover:bg-gray-300 p-2">
                        Add to playlist...
                    </div>
                </DropdownMenu>
            </div>
            <div class="border-2 w-2/3">{{ video.description }}</div>
        </div>

        <PlaylistModal
            v-if="modalOpen"
            :selectedVideoId="selectedVideoId" 
            :show="modalOpen" 
            @close="closePlaylistModal"
        />
    </AuthenticatedLayout>
    
</template>