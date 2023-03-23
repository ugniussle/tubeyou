<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps(['playlist', 'videos'])

console.log(props.videos)

const main = ref(null)
</script>

<template>
    <Head title="Homepage"/>
    <AuthenticatedLayout :main="main">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ playlist.title }}</h2>
        </template>

        <div ref="main" class="grid grid-flow-row grid-rows-max gap-4 grid-cols-1 text-left text-xl pl-24 p-10">
            <div 
                class="w-full border-2 p-2 flex"
                v-for="video in videos"
                key="video.id"
                :video="video"
            >
                <Link :href="'/videos/'+video.url_token">
                    <img class="object-contain aspect-video w-64 bg-black" :src="video.thumbnail_asset">
                </Link>
                
                <div class="p-2">
                    <Link :href="'/videos/'+video.url_token">
                        <span class="block">{{ video.title }}</span>
                    </Link>
                    
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>