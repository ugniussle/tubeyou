<script setup>
import { onMounted, ref } from 'vue'
import Tooltip from './Tooltip.vue'
import Modal from './Modal.vue'

const props = defineProps(['videoInfo'])

/**
* @type {HTMLMediaElement}
*/
var video = null

/**
* @type {HTMLMediaElement}
*/
var container = null

/**
* @type {HTMLMediaElement}
*/
var controls = null

const seekBar = ref(null)
const settingsMenu = ref(null)
const qualityList = ref(null)
const seekTime = ref(0)
const isVideoPlaying = ref(false)
const lastVolume = ref(0.5)
const muted = ref(false)

const showHelp = ref(false)

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
    if(settingsMenu.value.classList.contains("hidden")){
        settingsMenu.value.classList.add("flex")
        settingsMenu.value.classList.remove("hidden")
    } else {
        settingsMenu.value.classList.add("hidden")
        settingsMenu.value.classList.remove("flex")
    }
}

const updateVolume = (volume) => {
    if(muted.value) {
        muted.value = false
    }

    if(volume < 0) {
        volume = 0.0
    }

    if(volume > 1) {
        volume = 1.0
    }

    video.volume = volume
    lastVolume.value = volume

    if(volume == 0.0) {
        muted.value = true
    }
}

const handleVolumeSlider = () => {
    let volume = parseFloat(document.getElementById("volumeSlider").value)

    updateVolume(volume)
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

const hideControls = () => {
    controls.classList.add("hidden")
}

var controlsTimeoutID = 0

const showControls = () => {
    controls.classList.remove("hidden")

    clearTimeout(controlsTimeoutID)

    if(!video.paused) {
        controlsTimeoutID = setTimeout(hideControls, 2000)
    }
}

const setupVideo = () => {
    video.addEventListener("click", togglePlay)
    video.addEventListener("timeupdate", handleTimeUpdate)
    video.currentTime = 0.00000001

    container.addEventListener("mouseover", showControls)


}

const setupKeyboardControls = () => {
    video.addEventListener("keydown", (event) => {
        switch(event.key) {
            case " ":
                event.preventDefault()
                togglePlay()
                break
            case "m":
                event.preventDefault()
                toggleMute()
                break
            case "f":
                event.preventDefault()
                toggleFullscreen()
                break
            case "ArrowLeft":
                video.currentTime -= 10
                event.preventDefault()
                break
            case "ArrowRight":
                video.currentTime += 10
                event.preventDefault()
                break
            case "ArrowUp":
                updateVolume(lastVolume.value + 0.05)
                event.preventDefault()
                document.getElementById("volumeSlider").value = lastVolume.value + 0.05
                break
            case "ArrowDown":
                updateVolume(lastVolume.value - 0.05)
                event.preventDefault()
                document.getElementById("volumeSlider").value = lastVolume.value - 0.05
                break
        }

        showControls()
    })
}

onMounted(() => {
    video = document.getElementById("video")

    video.volume = lastVolume.value
    container = document.getElementById("videoContainer")
    controls = document.getElementById("controls")

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
    setupKeyboardControls()
})

console.log(props.videoInfo.asset)
</script>

<template>
    <div id="videoContainer">
        <!-- video -->
        <video id="video" preload="metadata" class="w-full block m-2 ml-0 mb-0 aspect-video bg-black" :muted="muted">
            <source :src="'/'+videoInfo.asset.video_1080p">
            Your browser does not support the video tag.
        </video>

        <!-- video controls -->
        <div id="controls" class="-mb-12 w-full h-12 bottom-0 -translate-y-12 text-white bg-black/50">
            <Tooltip :teleportLocation="'#videoContainer'">
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
                <input id="volumeSlider" type="range" min="0" max="1" step="0.05" value="0.5" @input="handleVolumeSlider()" class="w-24">

                <!-- time -->
                <div class="ml-2 flex justify-center items-center">
                    <div>
                        <span id="currentTime">0:00</span>
                        <span> / </span>
                        <span id="totalTime">?:?</span>
                    </div>
                </div>

                <div class="grow"></div>

                <svg @click="(e) => openSettings(e)" stroke="currentColor" stroke-width="10" stroke-linecap="round" fill="none" class="h-12 w-12 mr-2" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="30"/>
                </svg>

                <div ref="settingsMenu" class="flex-col hidden fixed bg-gray-700/70 w-48 bottom-14 right-0">
                    <div class="p-2 cursor-pointer">
                        Quality
                    </div>

                    <div ref="qualityList"></div>

                    <div class="p-2 cursor-pointer" @click="showHelp = true">
                        Help
                        <Modal :show="showHelp" @close="() => showHelp = false">
                            <h1>Keyboard controls:</h1>
                                <p><span class="italic">f</span> - fullscreen</p>
                                <p><span class="italic">arrow left / right</span> - seek by 10 seconds</p>
                                <p><span class="italic">arrow up / down</span> - change volume</p>
                                <p><span class="italic">space</span> - pause / resume video</p>
                                <p><span class="italic">m</span> - mute / unmute video</p>
                        </Modal>
                    </div>
                </div>

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
