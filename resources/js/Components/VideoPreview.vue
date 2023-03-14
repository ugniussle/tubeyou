<script setup>
defineProps(['video'])
</script>

<template>
    <div class="text-center">
        <a :href="route('videos.url_token', video.url_token)" class="rounded-sm hover:ring-red-500 hover:ring-2 w-1/3">
            <img :src="video.thumbnail_asset">
            <span>{{ video.title }}</span>
        </a>
    </div>
</template>
