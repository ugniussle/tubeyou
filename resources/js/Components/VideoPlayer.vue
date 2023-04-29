<script setup>
import { onMounted, ref} from 'vue';
const props = defineProps(['videoInfo']);


/**
* @type {HTMLMediaElement}
*/
var video = null
var seekBarElement = null
const isVideoPlaying = ref(false)
var lastVolume = ref(0.5)
var muted = ref(false)

const togglePlay = () => {
    if(video.paused) {
        video.play()
        isVideoPlaying.value = true
    } else {
        video.pause()
        isVideoPlaying.value = false
    }
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

const seek = (event) => {
    let seekTime = event.layerX / seekBar.clientWidth * video.duration
    video.currentTime = seekTime

    handleTimeUpdate()
}

const handleTimeUpdate = () => {
    let position = (video.currentTime / video.duration) * 100
    seekBarElement.firstChild.style.width = position + '%'
}

const setupVideo = () => {
    video.addEventListener("click", togglePlay)
    video.addEventListener("timeupdate", handleTimeUpdate);
}

onMounted(() => {
    video = document.getElementById("video")
    video.volume = lastVolume.value

    seekBarElement = document.getElementById("seekBar")

    document.getElementById("totalTime").innerText = video.duration

    setupVideo()
})
</script>

<template>
    <div>
        <!-- video -->
        <video id="video" class="w-full m-2 ml-0 mb-0 aspect-video bg-black" width="320" height="240" :muted="muted">
            <source :src="videoInfo.video_asset">
            Your browser does not support the video tag.
        </video>

        <!-- video controls -->
        <div class="-mb-12 w-full h-12 bottom-0 -translate-y-12 text-white bg-black/50">
            <div id="seekBar" @click="e => seek(e)" class="bg-white w-full h-1 hover:scale-y-[5] hover:-translate-y-2 transition-all">
                <div class="bg-red-600 w-0 h-full"></div>
            </div>
            <div class="flex">
                <!-- play button -->
                <svg stroke="currentColor"  viewBox="0 0 20 20" @click="togglePlay()"                                                                                                                                                                                                                                                      class="w-12 h-12" color="white">
                    <path v-show="!isVideoPlaying" id="playingIcon" d="M6 5 L6 15 L15 10 Z" fill="#FFFFFF"/>
                    <path v-show=" isVideoPlaying" id="pausedIcon"  d="M7 5 V15 M13 5 V15"  fill="#FFFFFF" stroke-width="2"/>
                </svg>
                <!-- volume icon -->
                <svg stroke="currentColor" class="h-12 w-12" @click="toggleMute()" viewBox="0 0 100 100">
                    <!-- <rect fill-opacity="0" x="0" y="0" width="100" height="100"/> -->
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

                <div>
                    <span id="currentTime">0:00</span>
                    <span id="totalTime"></span>
                </div>

                <div class="grow"></div>

                <svg stroke="currentColor" stroke-width="6" stroke-linecap="round" fill="none" class="h-12 w-12 mr-2" @click="toggleFullscreen()" viewBox="0 0 160 90">
                    <path d="M10 5 v20 Z h20"/>
                    <path d="M150 5 v20 Z h-20"/>
                    <path d="M10 85 v-20 Z h20"/>
                    <path d="M150 85 v-20 Z h-20"/>
                </svg>
            </div>
        </div>
    </div>
</template>
