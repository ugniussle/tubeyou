<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps(['playlists'])

const main = ref(null)
</script>

<template>
    <Head title="Homepage"/>
    <AuthenticatedLayout :main="main">
        <div ref="main" class="grid grid-flow-row grid-rows-max gap-4 grid-cols-1 text-left text-xl pl-24 p-10">
            <div 
                class="w-full border-2 p-2 flex"
                v-for="playlist in playlists"
                key="playlist.id"
                :playlist="playlist"
            >
                <a :href="'/playlists/'+playlist.url_token">
                    <img class="object-contain aspect-video w-64 bg-black" :src="playlist.thumbnail">
                </a>

                <!-- {{ playlist }} -->
                <div class="p-2">
                    <a :href="'/playlists/'+playlist.url_token">
                        <span class="block">{{ playlist.title }}</span>
                    </a>
                    
                    <span>Videos in playlist: {{ playlist.length }}</span>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>