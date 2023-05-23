<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps(['playlist'])
</script>

<template>
    <div class="w-full border-2 p-2 flex bg-white">
        <!-- thumbnail -->
        <Link :href="'/playlists/'+playlist.url_token">
            <img class="object-contain aspect-video h-32 bg-black" :src="'/'+playlist.thumbnail">
        </Link>

        <!-- info -->
        <div class="p-2">
            <Link :href="'/playlists/'+playlist.url_token">
                <span class="block">{{ playlist.title }}</span>
            </Link>

            <span>Videos in playlist: {{ playlist.length }}</span>
        </div>

        <div class="grow"></div>

        <main>
            <slot/>
        </main>
    </div>

</template>
