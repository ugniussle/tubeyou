<script setup>
import { onMounted, onBeforeUnmount} from 'vue';
const props = defineProps(['video']);

const togglePlay = (event) => {
        console.log(event)
        let video = event.target

        if(video.paused) {
            video.play()
        } else {
            video.pause()
        }
}

/**
* @param {HTMLMediaElement} video
*/
const setupVideo = (video) => {
    video.addEventListener("click", togglePlay)
}

/**
* @param {HTMLMediaElement} video
*/
const unsetupVideo = (video) => {
    video.removeEventListener("click", togglePlay)
}

onMounted(() => {
    const video = document.getElementById("video")

    setupVideo(video)
})

onBeforeUnmount(() => {
    console.log("unmounting")
    const video = document.getElementById("video")

    unsetupVideo(video)
})
</script>

<template>
    <video id="video" class="w-full m-2 ml-0 aspect-video bg-black" width="320" height="240">
        <source :src="video.video_asset">
        Your browser does not support the video tag.
    </video>
</template>
