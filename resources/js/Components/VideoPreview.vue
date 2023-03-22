<script setup>
import ProfilePicture from './ProfilePicture.vue';
import { ref, onMounted } from 'vue';

const props = defineProps(['video'])

const emit = defineEmits(['openPlaylistModal'])

const dropdown = ref(null)
const dropdownButton = ref(null)
const addButton = ref(null)

const showDropdown = (args) => {
    const event = args[0]
    dropdown.value.style.transform = "scale(1)"

    let yScroll = window.scrollY

    // if dropdown would go beyond screen boundries, attach it to the right
    if(window.innerWidth - event.pageX < dropdown.value.offsetWidth) {
        dropdown.value.style.right = 0 + 'px'
    } else {
        dropdown.value.style.left = event.pageX + 'px'
    }

    // if dropdown would go beyond screen boundries, attach it to the bottom
    if((window.innerHeight + yScroll) - event.pageY < dropdown.value.offsetHeight) {
        dropdown.value.style.bottom = 0 + 'px'
        dropdown.value.style.top = ''
    } else {
        dropdown.value.style.top = (event.pageY - yScroll) + 'px'
        dropdown.value.style.bottom = ''
    }

    dropdown.value.focus({focusVisible: false})
}

onMounted(() => {
    dropdownButton.value.addEventListener('click', (event) => {
        setTimeout(showDropdown, 20, [event])
    })
    
    dropdown.value.addEventListener('focusout', () => {
        setTimeout(() => {
            dropdown.value.style.transform = "scale(0)"
        }, 100)
    })

    addButton.value.addEventListener('click', () => {
        emit('openPlaylistModal', props.video.id)
    })
})
</script>

<template>
    <div class="text-center">
        <!-- thumbnail -->
        <a :href="route('videos.url_token', video.url_token)" class="rounded-sm w-2/6 h-1/6">
            <img class="bg-black aspect-video object-contain rounded-sm hover:ring-red-500 hover:ring-2 w-full" :src="video.thumbnail_asset"/>
        </a>

        <div class="p-1 flex">
            <!-- video info -->
            <div class="inline">
                <span class="block text-left">{{ video.title }}</span>
                <span class="block text-left">
                    <ProfilePicture class="inline" :picture="video.profilePicture" :size="'2rem'"/>
                    {{ video.username }}
                </span>
            </div>

            <!-- spacer -->
            <div class="grow"/>

            <!-- dropdown button -->
            <div class="cursor-pointer">
                <svg ref="dropdownButton" class="h-14 w-14 p-2" viewport="0 0 40 40" color="gray"><circle cx="20" cy="10" r="2"/><circle cx="20" cy="20" r="2"/><circle cx="20" cy="30" r="2"/></svg>
            </div>
            
            <!-- dropdown -->
            <div tabindex="0" style="transform: scale(0);" ref="dropdown" class="fixed bg-white whitespace-nowrap w-48 transition-all border-2 border-gray-700">
                <div ref="addButton" class="hover:cursor-pointer hover:bg-gray-300 p-2">
                    Add to playlist...
                </div>
            </div>
        </div>
    </div>
</template>
