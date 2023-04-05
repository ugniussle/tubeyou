<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DropdownMenu from '@/Components/Dropdown/DropdownMenu.vue';
import PlaylistPreview from '@/Components/PlaylistPreviewList.vue';
import DeletePlaylist from '@/Components/Dropdown/DeletePlaylist.vue';

const props = defineProps(['playlists'])

const main = ref(null)
</script>

<template>
    <Head title="Homepage"/>

    <AuthenticatedLayout :main="main">
        <div ref="main" class="grid grid-flow-row grid-rows-max gap-4 grid-cols-1 text-left text-xl pl-24 p-10">
            <!-- Create playlist button -->
            <Link href="/playlists/create">
                <div class="p-2 bg-white border-2 text-center font-bold tracking-wide">
                    <svg stroke="black" class="top-1/2 w-7 h-7 inline" viewBox="0 0 64 64"><path stroke-linecap="square" d="M32 0 L 32 64 M0 32 L 64 32" stroke-width="10"/></svg>
                    <span class="ml-2">Create a new playlist</span>
                </div>
            </Link>
            
            <!-- playlists -->
            <PlaylistPreview 
                v-for="playlist in playlists"
                key="playlist.id"
                :playlist="playlist"
            >
                <DropdownMenu>
                    <DeletePlaylist :playlist="playlist"></DeletePlaylist>
                </DropdownMenu>
            </PlaylistPreview>
        </div>
    </AuthenticatedLayout>
</template>