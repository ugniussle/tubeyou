<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;

const sidebar = ref(null);
const sidebarExpanded = ref(false);

const toggleSidebar = () => {
    sidebarExpanded.value = !sidebarExpanded.value
    console.log(sidebar.value.style)

    if(!sidebarExpanded.value) {
        sidebar.value.style.width='4rem'
        document.body.style.marginLeft='0rem'
    } else {
        sidebar.value.style.width='20rem'
        document.body.style.marginLeft='20rem'
    }

}

</script>

<template>
    <!-- Sidebar -->
    <div ref="sidebar" class="bg-white fixed left-0 top-0 w-16 h-screen overflow-y-auto border-2">
        <div class="mr-2 flex items-center">
            <!-- Hamburger Button -->
            <button
                @click="toggleSidebar"
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
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
        </div>
    </div>
</template>