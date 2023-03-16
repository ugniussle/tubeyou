<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import VideoPreview from '@/Components/VideoPreview.vue';
import { Head } from '@inertiajs/vue3';
defineProps(['videos'])
</script>

<template>
    <Head title="Homepage"/>
    <AuthenticatedLayout>
        <div class="grid grid-flow-row grid-rows-max gap-4 grid-cols-3 text-center text-xl m-10">
            <VideoPreview 
                v-for="video in videos"
                :key="video.id"
                :video="video"
            />
        </div>
    </AuthenticatedLayout>
    
</template>