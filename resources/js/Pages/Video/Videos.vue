<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import VideoPreview from '@/Components/VideoPreviewGrid.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import AddToPlaylist from '@/Components/Dropdown/AddToPlaylist.vue';

defineProps(['videos'])

const main = ref(null)

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
            >
                <!-- dropdown menu items -->
                <AddToPlaylist :video="video"/>
            </VideoPreview>
        </div>
    </AuthenticatedLayout>
</template>