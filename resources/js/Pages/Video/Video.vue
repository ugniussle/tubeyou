<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
defineProps(['videoInfo'])
</script>

<template>
    <AuthenticatedLayout>
        <div class="text-xl m-10">
            <video class="w-2/3 m-2 ml-0" controls width="320" height="240" autoplay>
                <source :src="videoInfo.video_asset">
                Your browser does not support the video tag.
            </video> 
            <div class="text-2xl">{{ videoInfo.title }}</div>
            <div class>{{ videoInfo.description }}</div>

        </div>
    </AuthenticatedLayout>
    
</template>