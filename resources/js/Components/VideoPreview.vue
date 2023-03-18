<script setup>
import ProfilePicture from './ProfilePicture.vue';

defineProps(['video'])
</script>

<template>
    <div class="text-center">
        <a :href="route('videos.url_token', video.url_token)" class="rounded-sm w-2/6 h-1/6">
            <img style="aspect-ratio: 16/9;object-fit: contain;" class="rounded-sm hover:ring-red-500 hover:ring-2 w-full" :src="video.thumbnail_asset">
            <div class="ml-1">
                <span class="block text-left">{{ video.title }}</span>
                <span class="block text-left">
                    <ProfilePicture class="inline" :picture="video.profilePicture" :size="'2rem'"/>
                    {{ video.username }}
                </span>
            </div>
        </a>
    </div>
</template>
