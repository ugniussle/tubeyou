<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import VideoPreview from '@/Components/VideoPreview.vue';
defineProps(['videos'])
</script>

<template>
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