<script setup>
import ProfilePicture from './ProfilePicture.vue';
import { Link } from '@inertiajs/vue3';
import DropdownMenu from './Dropdown/DropdownMenu.vue'

const props = defineProps(['video'])

</script>

<template>
    <div class="text-center">
        <!-- thumbnail -->
        <Link :href="route('videos.view', video.url_token)" class="rounded-sm w-2/6 h-1/6">
            <img class="bg-black aspect-video object-contain rounded-sm hover:ring-red-500 hover:ring-2 w-full" :src="'/'+video.asset.thumbnail_small"/>
        </Link>

        <div class="p-1 flex">
            <!-- video info -->
            <ProfilePicture class="inline mr-2 flex-none" :user="video.user" :size="'2.5rem'"/>

            <div class="inline flex-col">
                <!-- title -->
                <Link :href="route('videos.view', video.url_token)" class="block text-left">
                    {{ video.title.length > 35 ? video.title.slice(0,35) + '...' : video.title }}
                </Link>

                <!-- channel name -->
                <Link :href="route('channels.view', video.user.id)" class="text-left text-sm">
                    <div>
                        {{ video.user.username }}
                    </div>
                </Link>

                <!-- view count and date -->
                <div class="text-sm text-left">
                    <span>{{ video.views }} views</span>
                    <svg class="inline w-5" viewBox="0 0 10 10"><circle cx="5" cy="4" r="1"/></svg>
                    <span>{{ video.created_at.slice(0, 10) }} </span>
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
