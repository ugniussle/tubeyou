<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DropdownMenu from '@/Components/Dropdown/DropdownMenu.vue';
import VideoPreviewList from '@/Components/VideoPreviewList.vue';

const props = defineProps(['playlist', 'videos'])

const deleteVideoFromPlaylist = (playlistId, videoId) => {
    let playlistVideoForm = useForm({
        playlistId: playlistId,
        videoId: videoId,
    })

    playlistVideoForm.delete(route('playlistVideos.destroy'))
    //axios.delete(route('playlistVideos.destroy'), { data: playlistVideoForm })
}

const main = ref(null)
</script>

<template>
    <Head title="Homepage"/>
    <AuthenticatedLayout :main="main">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ playlist.title }}</h2>
        </template>

        <div ref="main" class="flex flex-col gap-4 text-left text-xl pl-24 p-10">
            <VideoPreviewList
                class="w-full border-2 p-2 flex bg-white"
                v-for="video in videos"
                key="video.id"
                :video="video"
            >
                <div @click="deleteVideoFromPlaylist(playlist.id, video.id)" class="hover:cursor-pointer hover:bg-gray-300 p-2">
                    Remove from playlist
                </div>
            </VideoPreviewList>
        </div>
    </AuthenticatedLayout>
</template>