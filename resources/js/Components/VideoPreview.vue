<script setup>
import ProfilePicture from './ProfilePicture.vue';

defineProps(['video'])
</script>

<template>
    <div class="text-center">
        <a :href="route('videos.url_token', video.url_token)" class="rounded-sm w-1/3">
            <img class="rounded-sm hover:ring-red-500 hover:ring-2" :src="video.thumbnail_asset">
            <div class="ml-1">
                <span class="block text-left">{{ video.title }}</span>
                <span class="block text-left">
                    <ProfilePicture class="inline" :picture="video.profilePicture" :size="6"/>
                    {{ video.username }}
                </span>
            </div>
        </a>
    </div>
</template>
