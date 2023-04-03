<script setup>
import Checkbox from './Forms/Checkbox.vue';
const props = defineProps(['playlist', 'videoId'])
const emit = defineEmits(['updateSelectedPlaylists'])

var checked = false

if(props.playlist.playlistVideos.find(
    playlistVideo => playlistVideo.video_id == props.videoId)
    ){
    checked = true
}

</script>

<template>
    <div class="p-2">
        <Checkbox 
            @update:checked="$id => emit('updateSelectedPlaylists', playlist.id, $id)"
            :checked="checked"
        />
        <span class="ml-2">
            {{ playlist.title }}

        </span>
    </div>
</template>