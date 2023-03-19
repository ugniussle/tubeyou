<script setup>
import ProfilePicture from './ProfilePicture.vue';
import { ref } from 'vue';
import { onMounted } from 'vue';

defineProps(['video'])

const dropdown = ref(null)
const button = ref(null)

const showDropdown = ref(false)

onMounted(() => {
    button.value.onclick = (event) => {
        dropdown.value.style.transform = "scale(1)"

        // offset dropdown if it would go off screen
        let xOffset = 0
        if(window.innerWidth - event.pageX < dropdown.value.clientWidth) {
            xOffset = window.innerWidth - event.pageX
        }

        let yOffset = 0
        if(window.innerHeight - event.pageY < dropdown.value.clientHeight) {
            yOffset = window.innerHeight - event.pageY
        }

        dropdown.value.style.left = (event.pageX - xOffset) + 'px'
        dropdown.value.style.top = (event.pageY - yOffset) + 'px'

        dropdown.value.focus()
    }

    dropdown.value.addEventListener('focusout', () => {
        dropdown.value.style.transform = "scale(0)"
        showDropdown.value = !showDropdown.value 
        })

    dropdown.value.addEventListener('focusin', () => {
            console.log('i was focused')
        })
})
</script>

<template>
    <div class="text-center">
        <a :href="route('videos.url_token', video.url_token)" class="rounded-sm w-2/6 h-1/6">
            <img class="aspect-video object-contain rounded-sm hover:ring-red-500 hover:ring-2 w-full" :src="video.thumbnail_asset"/>
        </a>
        <div class="p-1 grid grid-cols-8">
            <div class="col-span-7">
                <span class="block text-left">{{ video.title }}</span>
                <span class="block text-left">
                    <ProfilePicture class="inline" :picture="video.profilePicture" :size="'2rem'"/>
                    {{ video.username }}
                </span>
            </div>
            <div ref="button" class="cursor-pointer">
                <svg class="h-14 w-14 p-2" viewport="0 0 40 40" color="gray">
                    <circle cx="20" cy="10" r="2"/>
                    <circle cx="20" cy="20" r="2"/>
                    <circle cx="20" cy="30" r="2"/>
                </svg>
            </div>
            <div tabindex="0" style="transform: scale(0);" ref="dropdown" class="fixed bg-white focus:bg-green-500">
                <a href="#" class="block focus:bg-red-500">Link 1</a>
                <a href="#" class="block focus:bg-red-500">Link 2</a>
            </div>
        </div>
    </div>
</template>
