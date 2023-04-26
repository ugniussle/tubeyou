<script setup>
import { onMounted, ref} from 'vue';
const props = defineProps(['video']);


/**
* @type {HTMLMediaElement}
*/
var videoElement = null
const isVideoPlaying = ref(false)
var lastVolume = 0.5
var muted = ref(false)

const togglePlay = () => {
    if(videoElement.paused) {
        videoElement.play()
        isVideoPlaying.value = true
    } else {
        videoElement.pause()
        isVideoPlaying.value = false
    }
}

const updateVolume = () => {
    if(muted.value) {
        muted.value = false
    }
    let volume = parseFloat(document.getElementById("volumeSlider").value)
    videoElement.volume = volume
    lastVolume = volume
}

const toggleMute = () => {
    if(videoElement.muted) {
        videoElement.muted = false
        muted.value = false
        document.getElementById("volumeSlider").value = lastVolume
    } else {
        videoElement.muted = true
        muted.value = true
        document.getElementById("volumeSlider").value = "0.0"
    }
}

const setupVideo = () => {
    videoElement.addEventListener("click", togglePlay)
}

onMounted(() => {
    videoElement = document.getElementById("video")
    videoElement.volume = lastVolume

    setupVideo()
})
</script>

<template>
    <div>
        <!-- video -->
        <video id="video" class="w-full m-2 ml-0 mb-0 aspect-video bg-black" width="320" height="240">
            <source :src="video.video_asset">
            Your browser does not support the video tag.
        </video>

        <!-- video controls -->
        <div class="flex w-full h-12 bottom-0 -translate-y-12 text-white bg-black/50">
            <!-- play button -->
            <svg stroke="currentColor"  viewBox="0 0 20 20" @click="togglePlay()"                                                                                                                                                                                                                                                      class="w-12 h-12" color="white">
                <path v-show="!isVideoPlaying" id="playingIcon" d="M6 5 L6 15 L15 10 Z" fill="#FFFFFF" />
                <path v-show=" isVideoPlaying" id="pausedIcon"  d="M7 5 V15 M13 5 V15"  fill="#FFFFFF" stroke-width="2"/>
            </svg>
            <!-- volume icon -->
            <svg stroke="currentColor" class="h-12 w-12" @click="toggleMute()" viewBox="0 0 100 100">
                <rect fill-opacity="0" x="0" y="0" width="100" height="100"/>
                <g fill="currentColor">
                    <rect x="20" y="40" width="20" height="20"/>
                    <path d="M40 40 l10 -10 l0 40 l-10 -10Z"/>
                </g>
                <g>
                    <path v-show=" muted" stroke-width="3" d="M60 40 l20 20 M60 60 l20 -20"/>
                    <path v-show="!muted" stroke-width="4" d="M55 25 Q80 50 55 75" />
                </g>
            </svg>

            <!-- volume slider -->
            <input id="volumeSlider" type="range" min="0" max="1" step="0.01" value="0.5"                                                                                                    @change="updateVolume()">
        </div>
    </div>
</template>
