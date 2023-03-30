<script setup>
import TextAreaInput from '@/Components/Forms/TextAreaInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';
import ProfilePicture from '@/Components/ProfilePicture.vue';

const props = defineProps(['videoId', 'videoUrlToken'])

const form = useForm({
        videoId: props.videoId,
        body: ''
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

        <div>
            <div class="p-2 mb-2 flex" v-for="comment in comments">
                <ProfilePicture class="mr-2" :picture="comment.user.profile_picture" :size="'3rem'"/>
                <div class="flex flex-col">
                    <span>
                        <span class="text-gray-700 mr-2">
                            {{ comment.user.username }}
                        </span>  
                        <span>
                            {{ comment.created_at.slice(0, 10) }}
                        </span>
                    </span>

                    <span>{{ comment.body }}</span>
                </div>
            </div>
        </div>
    </div>
</template>