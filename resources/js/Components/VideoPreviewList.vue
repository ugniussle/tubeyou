<script setup>
import ProfilePicture from './ProfilePicture.vue';
import { Link } from '@inertiajs/vue3';
import DropdownMenu from './DropdownMenu.vue'

const props = defineProps(['video'])

</script>

<template>
    <div class="text-center">
        <div class="w-full p-1 flex">
            <!-- thumbnail -->
            <Link :href="route('videos.url_token', video.url_token)" class="rounded-sm">
                <img class="bg-black aspect-video object-contain rounded-sm hover:ring-red-500 hover:ring-2 h-32" :src="video.thumbnail_asset"/>
            </Link>

            <div class="flex">
                <!-- video info -->
                <ProfilePicture class="mx-2" :picture="video.profilePicture" :size="'2.5rem'"/>
                
                <div>
                    <!-- title -->
                    <Link :href="route('videos.url_token', video.url_token)" class="block text-left">
                        {{ video.title }}
                    </Link>
    
                    <!-- channel name, view count and date -->
                    <div class="text-sm">
                        <span>{{ video.username }}</span>
                        <svg class="inline w-5" viewBox="0 0 10 10"><circle cx="5" cy="4" r="1"/></svg>
                        <span>{{ video.views }} views</span>
                        <svg class="inline w-5" viewBox="0 0 10 10"><circle cx="5" cy="4" r="1"/></svg>
                        <span>{{ video.created_at.slice(0, 10) }} </span>
                    </div>
                </div>
            </div>

            <!-- spacer -->
            <div class="grow"/>

            <!-- dropdown with button -->
            <DropdownMenu>
                <main>
                    <slot/>
                </main>
            </DropdownMenu>
        </div>
    </div>
</template>
dropdown