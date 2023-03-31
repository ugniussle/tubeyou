<script setup>
import TextAreaInput from '@/Components/Forms/TextAreaInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';
import Comment from './Comment.vue';

const props = defineProps(['videoId', 'videoUrlToken'])

const form = useForm({
        videoId: props.videoId,
        body: '',
        parent: null
    })

const comments = ref([])

const postComment = async() => {
    axios.post(route('comments.store'), form)
    
    form.body = ''

    const response = await getComments()
    comments.value = response.data
}

const getComments = async() => {
    return axios.post(route('comments.get', props.videoUrlToken))
}

const replySetup = (target) => {
    target.nextSibling.style.display = 'block'
    target.style.display = 'none'
}

const postReply = (target, commentId) => {
    target.parentElement.style.display = 'none'
    target.parentElement.previousSibling.style.display = 'block'

    const input = target.previousSibling

    console.log(input.value)

    const replyForm = useForm({
        videoId: props.videoId,
        body: input.value,
        parent: commentId
    })

    axios.post(route('comments.store'), replyForm)

    input.value = ""
}

onMounted(async() => {
    const response = await getComments()
    comments.value = response.data

    console.log(comments.value)
})
</script>

<template>
    <div>
        <h2 class="text-xl">Comments</h2>
        <div>
            Write your own comment
            <TextAreaInput v-model="form.body" placeholder="Write your comment here..."/>
            <PrimaryButton @click="postComment()">Post</PrimaryButton>
        </div>

        <Comment 
            v-for="comment in comments" 
            :comment="comment" 
            @postReply="postReply" 
            @replySetup="replySetup"
        />
    </div>
</template>