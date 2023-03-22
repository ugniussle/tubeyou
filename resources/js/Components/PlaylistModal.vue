<script setup>
import { computed, onMounted, onUnmounted, watch, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import TextInput from './Forms/TextInput.vue';
import SelectInput from './Forms/SelectInput.vue';
import InputLabel from './Forms/InputLabel.vue';
import PrimaryButton from './PrimaryButton.vue';
import PlaylistModalItem from './PlaylistModalItem.vue';

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
    playlists: {
        default: null
    }
});

const emit = defineEmits(['close']);

var selectedPlaylists = []

const saveVideoToPlaylists = () => {
    for (let i = 0; i < selectedPlaylists.length; i++) {
        let playlist = selectedPlaylists[i]
        console.log(playlist)

        if(playlist.action === true) {
            console.log('saving video', props.selectedVideoId, 'to playlists', selectedPlaylists)
            saveVideoToPlaylist(playlist.id, props.selectedVideoId)
        } else if(playlist.action === false) {
            console.log('deleting video', props.selectedVideoId, 'to playlists', selectedPlaylists)
            deleteVideoFromPlaylist(playlist.id, props.selectedVideoId, playlist.url_token)
        }
    }

    selectedPlaylists = []

    close()
}

const saveVideoToPlaylist = (playlistId, videoId) => {
    console.log(playlistId, videoId)
    let playlistVideoForm = useForm({
        playlistId: playlistId,
        videoId: videoId
    })

    playlistVideoForm.post(route('playlistVideos.store'))
}

const deleteVideoFromPlaylist = (playlistId, videoId) => {
    let playlistVideoForm = useForm({
        playlistId: playlistId,
        videoId: videoId
    })

    const playlistVideo = 

    console.log(route('playlistVideos.destroy', playlistUrlToken))

    playlistVideoForm.delete(
        route('playlistVideos.destroy', playlistVideo)
    )
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

    console.log(selectedPlaylists)
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

onMounted(() => {
    document.addEventListener('keydown', closeOnEscape)
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
                        v-show="show"
                        class="-translate-x-1/2 top-1/4 left-1/2  absolute mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all w-full sm:mx-auto md:w-3/12 text-2xl p-2"
                    >
                        <PlaylistModalItem 
                            v-for="playlist in playlists"
                            :key="playlist.id"
                            :playlist="playlist"
                            :videoId="selectedVideoId"
                            @updateSelectedPlaylists="($playlistId, $action) => selectPlaylist($playlistId, $action)"
                        />
                        <PrimaryButton @click="saveVideoToPlaylists">Save</PrimaryButton>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>
