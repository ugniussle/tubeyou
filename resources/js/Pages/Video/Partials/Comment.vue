<script setup>
import { ref } from 'vue';
import ProfilePicture from '@/Components/ProfilePicture.vue';
import TextAreaInput from '@/Components/Forms/TextAreaInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps(['comment', 'child'])

const emit = defineEmits(['postReply', 'replySetup'])

const showReplies = ref(false)
</script>

<template>
<div class="p-2 flex">
    <ProfilePicture class="mr-2" :user="comment.user" :size="'3rem'"/>
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
    
        <!-- reply -->
        <div v-if="!child">
            <div>
                <span @click="$emit('replySetup', $event.target)" class="w-10 hover:underline hover:cursor-pointer text-gray-500">Reply</span>
                <span class="hidden">
                    <TextAreaInput placeholder="Write your comment here..."/>
                    <PrimaryButton class="mb-2" @click="$emit('postReply', $event.target, comment.id)">Reply</PrimaryButton>
                </span>

                <div class="ml-20 hover:cursor-pointer text-gray-500 w-28" v-if="comment.replies.length" @click="showReplies = !showReplies">
                    Replies ({{ comment.replies.length }})
                    <svg v-if="!showReplies" viewBox="0 0 10 10" class="w-8 h-8 inline"><path stroke="currentColor" fill="none" d="M1 3 l4 4 l4 -4" /> </svg>
                    <svg v-else viewBox="0 0 10 10" class="w-8 h-8 inline"><path stroke="currentColor" fill="none" d="M1 7 l4 -4 l4 4" /> </svg>
                </div>
            </div>
    
            <div v-if="showReplies">
                <Comment v-for="reply in comment.replies" :comment="reply" :child="true"/>
            </div>
        </div>
    </div>
</div>
</template>