<script setup>
import { onMounted, onUnmounted, watch, ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import PrimaryButton from './PrimaryButton.vue';
import PlaylistModalItem from './PlaylistModalItem.vue';
import axios from 'axios';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    selectedVideoId: {
        type: Number,
        default: null
    },
});

const emit = defineEmits(['close']);

var selectedPlaylists = []

const saveVideoToPlaylists = () => {
    for (let i = 0; i < selectedPlaylists.length; i++) {
        let playlist = selectedPlaylists[i]

        if(playlist.action === true) {
            saveVideoToPlaylist(playlist.id, props.selectedVideoId)
        } else if(playlist.action === false) {
            deleteVideoFromPlaylist(playlist.id, props.selectedVideoId, playlist.url_token)
        }
    }

    selectedPlaylists = []

    close()
}

const saveVideoToPlaylist = (playlistId, videoId) => {
    let playlistVideoForm = useForm({
        playlistId: playlistId,
        videoId: videoId
    })

    axios.post(route('playlistVideos.store'), playlistVideoForm)
}

const deleteVideoFromPlaylist = (playlistId, videoId) => {
    let playlistVideoForm = useForm({
        playlistId: playlistId,
        videoId: videoId,
    })

    axios.delete(route('playlistVideos.destroy'), { data: playlistVideoForm })
}

const selectPlaylist = (playlistId, action) => {
    let index = -1

    for(let i = 0; i < selectedPlaylists.length; i++) {
        let playlist = selectedPlaylists[i]

        if(playlist.id == playlistId) index = i
    }

    if(index == -1) {
        selectedPlaylists.push({
            id:     playlistId,
            action: action
        })
    } else {
        selectedPlaylists.splice(index, 1)
    }
}

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = null;
        }
    }
);

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

var playlists = ref(null)

const getUserPlaylists = async () => {
    return await axios.get(route('playlists.getPlaylists'))
}

onMounted(async() => {
    document.addEventListener('keydown', closeOnEscape)

    const response = await getUserPlaylists()
    playlists.value = response.data
});

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = null;
});

</script>

<template>
    <teleport to="body">
        <transition leave-active-class="duration-200">
            <div v-show="show" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" scroll-region>
                <div v-show="show" class="fixed inset-0 transform transition-all bg-black/75" @click="close">
                    <div class="absolute inset-0 opacity-0" />
                </div>

                <transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div
                        v-if="playlists"
                        class="-translate-x-1/2 top-1/4 left-1/2  absolute mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all w-full sm:mx-auto md:w-3/12 text-2xl p-2"
                    >
                        <div v-if="playlists.length === 0">
                            You have no playlists. You can create a playlist 
                            <Link class="text-blue-500" :href="route('playlists.create')">here</Link>.
                        </div>

                        <div v-else>
                            <PlaylistModalItem 
                                v-for="playlist in playlists"
                                :key="playlist.id"
                                :playlist="playlist"
                                :videoId="selectedVideoId"
                                @updateSelectedPlaylists="($playlistId, $action) => selectPlaylist($playlistId, $action)"
                            />
                            <PrimaryButton @click="saveVideoToPlaylists">Save</PrimaryButton>
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>
