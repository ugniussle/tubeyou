<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import VideoPreview from '@/Components/VideoPreview.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps(['videos'])

const main = ref(null)
</script>

<template>
    <Head title="Homepage"/>
    <AuthenticatedLayout :main="main">
        <div 
            class="grid grid-flow-row grid-rows-max gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 text-center text-xl pl-24 p-10"
            ref="main"
            >
            <VideoPreview 
                v-for="video in videos"
                :key="video.id"
                :video="video"
            />
        </div>
    </AuthenticatedLayout>
    
</template>