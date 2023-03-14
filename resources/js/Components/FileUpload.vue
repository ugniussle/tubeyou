<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Resumable from 'resumablejs';

const props = defineProps(['modelValue', 'csrf_token'])

const emit = defineEmits(['update:modelValue'])

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

const validateFile = function(file) {
    if(file.file.type.slice(0, 5) === 'video') {
        return true
    } else {
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
    document.getElementById('videoProgress').value = Math.floor(file.progress()*100)
    document.getElementById('videoProgress').innerHTML = Math.floor(file.progress()*100) + '%'
})

r.on('fileSuccess', function(file, message) {
    console.log(message)
    console.log(typeof(message))
    
    const response = JSON.parse(message)

    console.log(response)

    emit('update:modelValue', response.path)
    console.log(response.path)
})
</script>

<template>
    <div class="text-center">
        <SecondaryButton id="browseVideoFiles" @vnodeMounted="setupUpload($event)">Browse...</SecondaryButton>
        <div id="progressDisplay" class="hidden border-black">
            <span class="block" id="videoFileDisplay"></span>

            <label for="videoProgress">Upload progress:</label>
            <progress id="videoProgress" max="100" value="0"></progress>
        </div>
        <SecondaryButton   id="startUploadButton" class="hidden" @click="startStopUpload($event)">Start file upload</SecondaryButton>

    </div>
</template>
