<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Resumable from 'resumablejs';
import { ref } from 'vue';

const props = defineProps(['modelValue', 'csrf_token'])

const emit = defineEmits(['update:modelValue', 'updateErrorMessage'])

const progressBar = ref(null)

var r = new Resumable({
    chunkSize: 1 * 1024 * 1024,
    simultaneousUploads: 3,
    testChunks: false,
    target: route('videos.uploadVideo'),
    method: 'multipart',
    maxFiles: 1,
    query: {'_token': props.csrf_token}
});

const setupUpload = function(event) {
    r.assignBrowse(event.el, false)
}

r.on('fileAdded', function(file, event){
    console.log(props.csrf_token)
    if(!validateFile(file)) {
        console.log('file is invalid')
        document.getElementById('progressDisplay').style.display = 'none'
        document.getElementById('startUploadButton').style.display = 'none'
        document.getElementById('videoFileDisplay').innerHTML = ''
        return
    }

    // console.log(file.file.type)
    console.log(file, event)

    document.getElementById('progressDisplay').style.display = 'block'
    document.getElementById('startUploadButton').style.display = 'inline-block'
    document.getElementById('videoFileDisplay').innerHTML = file.fileName
})

r.on('fileProgress', function(file) {
    let progress = Math.floor(file.progress()*100)

    progressBar.value.value = progress
    progressBar.value.innerHTML = progress + '%'
})

r.on('fileSuccess', function(file, message) {
    const response = JSON.parse(message)

    emit('update:modelValue', response.path)
})


const validateFile = function(file) {
    if(file.file.type.slice(0, 5) === 'video') {
        emit('updateErrorMessage', '')
        return true
    } else {
        console.log('emitting')
        emit('updateErrorMessage', 'File is not a video. Choose a video file.')
        return false
    }
}

function startStopUpload(event) {
    if(r.files.length == 0) {
        console.log('add files first')
        return
    }

    console.log('starting download')
    
    if(r.isUploading()) {
        event.target.innerHTML = "Stop File Upload"
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
