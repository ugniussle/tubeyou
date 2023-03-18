<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ProfilePicture from '@/Components/ProfilePicture.vue';
import { ref } from 'vue';

defineProps(['videoInfo', 'channelInfo'])

const main = ref(null)
</script>

<template>
    <Head :title="videoInfo.title"/>
    <AuthenticatedLayout :main="main">
        <div class="text-xl m-10" ref="main">
            <video class="w-2/3 m-2 ml-0" controls width="320" height="240" autoplay>
                <source :src="videoInfo.video_asset">
                Your browser does not support the video tag.
            </video> 
            <div id="videoInfo" class="text-2xl w-2/3">
                <span class="block" id="videoTitle"> {{ videoInfo.title }} </span>
                <ProfilePicture class="inline mr-2" :picture="channelInfo.profile_picture" :size="'3rem'"/>
                <span id="channelName">{{ channelInfo.username }}</span>
                <span id="uploadDate" class="block">{{ videoInfo.upload_date }}</span>
                
            </div>
            <div class="border-2 w-2/3">{{ videoInfo.description }}</div>

        </div>
    </AuthenticatedLayout>
    
</template>