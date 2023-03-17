<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps(['main'])

const user = usePage().props.auth.user;

const sidebar = ref(null);
const sidebarExpanded = ref(false);

window.addEventListener('resize', () => {
    // display sidebar on top of content if screen wdith is below 768px
    // and the opposite if width is higher than 768px
    if(sidebarExpanded.value) {
        if(document.body.clientWidth < 768) {
            props.main.style.marginLeft='0rem'
        } else {
            props.main.style.marginLeft='16rem'
        }
    }
})

const toggleSidebar = () => {
    sidebarExpanded.value = !sidebarExpanded.value

    if(!props.main.classList.contains('transition-all')) {
        props.main.classList += ' transition-all'
    }

    if(!sidebarExpanded.value) {
        sidebar.value.style.width='4rem'
        props.main.style.marginLeft='0rem'
    } else {
        sidebar.value.style.width='20rem'
        if(document.body.clientWidth > 768) {
            props.main.style.marginLeft='16rem'
        }
    }

}

</script>

<template>
    <!-- Sidebar -->
    <button
        style="z-index: 1;"
        @click="toggleSidebar"
        class="fixed top:0 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
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
    <div ref="sidebar" style="width:4rem" class="bg-white fixed left-0 top-0 h-screen overflow-y-auto transition-all">
        <div class="mr-2 flex items-center">
            <!-- Hamburger Button -->
            
        </div>
    </div>
</template>