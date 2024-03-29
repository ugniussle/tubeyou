<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Resumable from 'resumablejs';
import { ref } from 'vue';

const props = defineProps(['modelValue', 'csrfToken'])

const emit = defineEmits(['update:modelValue', 'updateErrorMessage'])

const progressBar = ref(null)

var r = new Resumable({
    chunkSize: 1 * 1024 * 1024,
    simultaneousUploads: 3,
    testChunks: false,
    target: route('videos.uploadVideo'),
    method: 'multipart',
    maxFiles: 1,
    query: {'_token': props.csrfToken}
});

const setupUpload = (event) => {
    r.assignBrowse(event.el, false)
}

r.on('fileAdded', (file, event) => {
    if(!validateFile(file)) {
        document.getElementById('progressDisplay').style.display = 'none'
        document.getElementById('startUploadButton').style.display = 'none'
        document.getElementById('videoFileDisplay').innerHTML = ''
        return
    }

    document.getElementById('progressDisplay').style.display = 'block'
    document.getElementById('startUploadButton').style.display = 'inline-block'
    document.getElementById('videoFileDisplay').innerHTML = file.fileName
})

r.on('fileProgress', (file) => {
    let progress = Math.floor(file.progress()*100)

    progressBar.value.value = progress
    progressBar.value.innerHTML = progress + '%'
})

r.on('fileSuccess', (file, message) => {
    const response = JSON.parse(message)

    emit('update:modelValue', response.path)
})


const validateFile = (file) => {
    if(file.file.type.slice(0, 5) === 'video') {
        emit('updateErrorMessage', '')
        return true
    } else {
        emit('updateErrorMessage', 'File is not a video. Choose a video file.')
        return false
    }
}

const startStopUpload = (event) => {
    if(r.files.length == 0) {
        emit('updateErrorMessage', 'No files selected.')
        return
    }

    if(r.isUploading()) {
        event.target.innerHTML = "Pause File Upload"
    } else {
        event.target.innerHTML = "Start File Upload"
    }

    r.upload()
}
</script>

<template>
    <div>
        <SecondaryButton id="browseVideoFiles" @vnodeMounted="setupUpload($event)">Browse...</SecondaryButton>
            <div id="progressDisplay" class="hidden border-black">
                <span class="block" id="videoFileDisplay"></span>

                <label for="videoProgress">Upload progress:</label>
                <progress ref="progressBar" class="w-full h-10 rounded-sm bg-gray-500" id="videoProgress" max="100" value="0"></progress>
            </div>
        <SecondaryButton id="startUploadButton" class="hidden" @click="startStopUpload($event)">Start file upload</SecondaryButton>
    </div>
</template>
