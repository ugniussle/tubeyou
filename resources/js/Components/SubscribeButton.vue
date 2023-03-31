<script setup>
import {  onBeforeMount, ref } from 'vue';
import axios from 'axios';

const props = defineProps(['channel'])

const subscribeToChannel = async() => {
    const response = (await axios.post(route('subscriptions.subscribe', props.channel.id))).data

    console.log(response)

    if(response['action'] == 'deleted') {
        subscribed.value = false
    } else {
        subscribed.value = true
    }
    
}

const getSubscriptionStatus = async() => {
    const response = (await axios.get(route('subscriptions.getStatus', props.channel.id))).data

    return response
}

const subscribed = ref(false)

onBeforeMount(async() => {
    const response = await getSubscriptionStatus()
    const status = response['status']

    if(status == 'subscribed') {
        subscribed.value = true 
    } else {
        subscribed.value = false 
    }
})

</script>

<template>
    <div v-if="subscribed" @click="subscribeToChannel()" class="px-2 py-3 h-14 border rounded-full tracking-wide bg-green-300 text-xl hover:cursor-pointer">
        Subscribed
    </div>
    <div v-else @click="subscribeToChannel()" class="px-2 py-3 h-14 border rounded-full tracking-wide bg-red-300 text-xl hover:cursor-pointer">
        Subscribe
    </div>
</template>