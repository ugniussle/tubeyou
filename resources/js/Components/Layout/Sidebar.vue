<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps(['main', 'sidebarHidden'])

const user = usePage().props.auth.user;

const sidebarHidden = props.sidebarHidden ? true : false;

const sidebar = ref(null);
const sidebarExpanded = ref(false);

var sidebarClosedWidth = null
var sidebarOpenWidth = null

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

if(sidebarHidden) {
    sidebarClosedWidth = '0'
    sidebarOpenWidth = '12rem'
} else {
    sidebarClosedWidth = '4rem'
    sidebarOpenWidth = '12rem'
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

</script>

<template>
    <button
        @click="toggleSidebar"
        class="fixed top:0 inline items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
        style="z-index: 1;"
    >
        <svg class="h-12 w-12" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path
                :class="{
                    'inline-flex': !sidebarExpanded,
                }"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1"
                d="M4 6h16M4 12h16M4 18h16"
            />
        </svg>
    </button>

    <div 
        ref="sidebar" 
        class="bg-white fixed left-0 top-16 h-screen overflow-y-auto transition-all"
        :style="'width:'+sidebarClosedWidth"
    >
        <div class="mr-2 flex items-center">
            <main>
                <slot/>
            </main>
        </div>
    </div>


</template>