<script setup>
import { onMounted, ref} from 'vue'
import Tooltip from './Tooltip.vue'

const props = defineProps(['videoInfo'])

/**
* @type {HTMLMediaElement}
*/
var video = null
var container = null
const seekBar = ref(null)
const seekTime = ref(0)
const isVideoPlaying = ref(false)
const lastVolume = ref(0.5)
const muted = ref(false)

const togglePlay = () => {
    if(video.paused) {
        video.play()
        isVideoPlaying.value = true
    } else {
        video.pause()
        isVideoPlaying.value = false
    }
}

const toggleFullscreen = () => {
    if(document.fullscreenElement) {
        document.exitFullscreen()
    } else {
        container.requestFullscreen()
    }
}

const openSettings = () => {
    console.log("opening settings")
}

const updateVolume = () => {
    if(muted.value) {
        muted.value = false
    }

    let volume = parseFloat(document.getElementById("volumeSlider").value)
    video.volume = volume
    lastVolume.value = volume

    if(volume == 0.0) {
        muted.value = true
    }
}

const toggleMute = () => {
    if(video.muted) {
        muted.value = false
        document.getElementById("volumeSlider").value = lastVolume.value
    } else {
        muted.value = true
        document.getElementById("volumeSlider").value = "0.0"
    }
}

const updateSeekTime = (event) => {
    seekTime.value = event.layerX / seekBar.value.clientWidth * video.duration
}

const seek = () => {
    video.currentTime = seekTime.value

    handleTimeUpdate()
}

const handleTimeUpdate = () => {
    let position = (video.currentTime / video.duration) * 100
    seekBar.value.firstChild.style.width = position + '%'
    document.getElementById("currentTime").innerText = formatTime(video.currentTime)
    document.getElementById("totalTime").innerText = formatTime(video.duration)
}

const formatTime = (seconds) => {
    let secondPart = Math.floor(seconds % 60)
    let minutePart = Math.floor(seconds / 60)
    let hourPart = Math.floor(seconds / (60 * 60))

    if(secondPart < 10) secondPart = '0' + secondPart

    if(hourPart < 1) {
        return minutePart + ":" + secondPart
    } else {
        return hourPart + ":" + minutePart + ":" + secondPart
    }
}

const setupVideo = () => {
    video.addEventListener("click", togglePlay)
    video.addEventListener("timeupdate", handleTimeUpdate);
    video.currentTime = 0.00000001
}

onMounted(() => {
    video = document.getElementById("video")
    video.volume = lastVolume.value
    container = document.getElementById("container")

    addEventListener("fullscreenchange", () => {
        controls = document.getElementById("controls")

        if(document.fullscreenElement == null) {
            controls.classList.add("-translate-y-12")
            controls.classList.remove("-translate-y-14")
        } else {
            controls.classList.remove("-translate-y-12")
            controls.classList.add("-translate-y-14")
        }
    })

    setupVideo()
})
</script>

<template>
    <div id="container">
        <!-- video -->
        <video id="video" preload="metadata" class="w-full block m-2 ml-0 mb-0 aspect-video bg-black" :muted="muted">
            <source :src="videoInfo.video_asset">
            Your browser does not support the video tag.
        </video>

        <!-- video controls -->
        <div id="controls" class="-mb-12 w-full h-12 bottom-0 -translate-y-12 text-white bg-black/50">
            <Tooltip :teleportLocation="'#container'">
                <template #tooltipMessage>
                    {{ formatTime(seekTime) }}
                </template>

                <!-- seek bar -->
                <div ref="seekBar" @mousemove="e => updateSeekTime(e)" @click="seek()" class="bg-white w-full h-1 hover:scale-y-[5] -translate-y-1 hover:-translate-y-3 transition-all">
                    <div class="bg-blue-600 w-0 h-full"></div>
                </div>
            </Tooltip>

            <div class="flex -mt-1">
                <!-- play button -->
                <svg stroke="currentColor"  viewBox="0 0 20 20" @click="togglePlay()"                                                                                                                                                                                                                                                      class="w-12 h-12" color="white">
                    <path v-show="!isVideoPlaying" id="playingIcon" d="M6 5 L6 15 L15 10 Z" fill="#FFFFFF"/>
                    <path v-show=" isVideoPlaying" id="pausedIcon"  d="M7 5 V15 M13 5 V15"  fill="#FFFFFF" stroke-width="2"/>
                </svg>

                <!-- volume icon -->
                <svg stroke="currentColor" class="h-12 w-12" @click="toggleMute()" viewBox="0 0 100 100">
                    <g fill="currentColor">
                        <rect x="20" y="40" width="20" height="20"/>
                        <path d="M40 40 l10 -10 l0 40 l-10 -10Z"/>
                    </g>

                    <g fill="none">
                        <path v-show=" muted" stroke-width="3" d="M60 40 l20 20 M60 60 l20 -20"/>
                        <path v-show="!muted" stroke-width="4" d="M55 25 Q80 50 55 75"/>
                        <path v-show="!muted && lastVolume > 0.5" stroke-width="4" d="M65 20 Q95 50 65 80" />
                    </g>
                </svg>

                <!-- volume slider -->
                <input id="volumeSlider" type="range" min="0" max="1" step="0.05" value="0.5" @input="updateVolume()" class="w-24">

                <!-- time -->
                <div class="ml-2 flex justify-center items-center">
                    <div class="">
                        <span id="currentTime">0:00</span>
                        <span> / </span>
                        <span id="totalTime">?:?</span>
                    </div>
                </div>

                <div class="grow"></div>

                <svg @click="openSettings()" stroke="currentColor" stroke-width="10" stroke-linecap="round" fill="none" class="h-12 w-12 mr-2" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="30"/>
                </svg>

                <svg @click="toggleFullscreen()" stroke="currentColor" stroke-width="6" stroke-linecap="round" fill="none" class="h-12 w-12 mr-2" viewBox="0 0 160 90">
                    <path d="M10 5 v20 Z h20"/>
                    <path d="M150 5 v20 Z h-20"/>
                    <path d="M10 85 v-20 Z h20"/>
                    <path d="M150 85 v-20 Z h-20"/>
                </svg>
            </div>
        </div>
    </div>
</template>
