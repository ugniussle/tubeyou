<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import SidebarItem from './SidebarItem.vue';
import ProfilePicture from '../ProfilePicture.vue';

const props = defineProps(['main', 'sidebarHidden'])

const user = usePage().props.auth.user;

const sidebarHidden = props.sidebarHidden ? true : false;
const sidebar = ref(null);
const sidebarExpanded = ref(false);
var sidebarClosedWidth = null
var sidebarOpenWidth = null

if(sidebarHidden) {
    sidebarClosedWidth = '0'
    sidebarOpenWidth = '12rem'
} else {
    sidebarClosedWidth = '4rem'
    sidebarOpenWidth = '12rem'
}

const getSubscriptions = async() => {
    return (await axios.get(route('subscriptions.getSubscriptions'))).data
}


if(!sidebarHidden) {
    window.addEventListener('resize', () => {
        // display sidebar on top of content if screen width is below 768px
        // and add a margin to main if width is higher than 768px
        if(sidebarExpanded.value) {
            if(document.body.clientWidth < 768) {
                props.main.style.marginLeft='0rem'
            } else {
                props.main.style.marginLeft='8rem'
            }
        }
    })
}

const toggleSidebar = () => {
    sidebarExpanded.value = !sidebarExpanded.value

    if(!sidebarHidden && !props.main.classList.contains('transition-all')) {
        props.main.classList += ' transition-all'
    }

    if(!sidebarExpanded.value) {    // close
        sidebar.value.style.width = sidebarClosedWidth

        if(!sidebarHidden) {
            props.main.style.marginLeft = '0rem'
        }
    } else {                        // open
        sidebar.value.style.width = sidebarOpenWidth

        if(!sidebarHidden && document.body.clientWidth > 768) {
            props.main.style.marginLeft = '8rem'
        }
    }
}

var subscriptions = ref(null)

onMounted(async() => {
    const response = await getSubscriptions()

    subscriptions.value = response
})
</script>

<template>
    <button
        @click="toggleSidebar"
        class="fixed top:0 inline items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
        style="z-index: 1;"
    >
        <svg class="h-12 w-12" stroke="currentColor" fill="none" viewBox="0 0 512 512"><path :class="{'inline-flex': !sidebarExpanded}" stroke-linecap="round" stroke-linejoin="round" stroke-width="20" d="M80 128 h352 M80 256 h352 M80 384 h352"/></svg>
    </button>

    <div 
        ref="sidebar" 
        class="bg-white fixed left-0 top-16 h-screen overflow-y-auto transition-all"
        :style="'width:'+sidebarClosedWidth"
    >
        <div class="flex items-center space-y-24 text-gray-400">
            <main class="w-full">
                <SidebarItem :link="route('playlists.index')">
                    <svg class="inline w-12 h-12" fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"><g><path d="M48 32 l 160 0"/><path d="M48 128 l 160 0"/><path d="M48 224 l 160 0"/></g><g><path d="M336 32 l 0 384" stroke-width="50"/><path d="M336 64 c 160 85 145 160 95 225" fill="none" stroke-width="50"/><circle cx="273" cy="415" r="86" stroke-width="5"/></g></svg>
                    <span class="pl-10">
                        Playlists
                    </span>
                </SidebarItem>

                <slot/>

                <div class="mt-20"/>

                <SidebarItem v-for="subscription in subscriptions" :link="route('channels.view', subscription.channel.id)">
                    <ProfilePicture :user="subscription.channel" :size="'3rem'"/>
                    <span class="pl-10">
                        {{ subscription.channel.username }}
                    </span>
                </SidebarItem>
            </main>
        </div>
    </div>


</template>