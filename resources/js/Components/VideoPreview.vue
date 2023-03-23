<script setup>
import ProfilePicture from './ProfilePicture.vue';
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import DropdownMenu from './DropdownMenu.vue'

const props = defineProps(['video'])

const emit = defineEmits(['openPlaylistModal'])

const addToPlaylistButton = ref(null)

onMounted(() => {
    addToPlaylistButton.value.addEventListener('click', () => {
        emit('openPlaylistModal', props.video.id)
    })
})
</script>

<template>
    <div class="text-center">
        <!-- thumbnail -->
        <Link :href="route('videos.url_token', video.url_token)" class="rounded-sm w-2/6 h-1/6">
            <img class="bg-black aspect-video object-contain rounded-sm hover:ring-red-500 hover:ring-2 w-full" :src="video.thumbnail_asset"/>
        </Link>

        <div class="p-1 flex">
            <!-- video info -->
            <ProfilePicture class="inline mr-2" :picture="video.profilePicture" :size="'2.5rem'"/>
            
            <div class="inline">
                <!-- title -->

                <Link :href="route('videos.url_token', video.url_token)" class="block text-left">
                    {{ video.title }}
                </Link>

                <!-- channel name -->
                <div class="text-left text-sm">
                    {{ video.username }}  
                </div>

                <!-- view count and date -->
                <div class="text-sm">
                    <span>{{ video.views }} views</span>
                    <svg class="inline w-5" viewBox="0 0 10 10">
                        <circle cx="5" cy="4" r="1"/>
                    </svg>
                    <span>{{ video.created_at.slice(0, 10) }} </span>
                    
                </div>
            </div>

            <!-- spacer -->
            <div class="grow"/>

            <!-- dropdown with button -->
            <DropdownMenu>
                <div ref="addToPlaylistButton" class="hover:cursor-pointer hover:bg-gray-300 p-2">
                    Add to playlist...
                </div>
            </DropdownMenu>
        </div>
    </div>
</template>
dropdown