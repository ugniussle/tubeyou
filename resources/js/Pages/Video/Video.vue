<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
defineProps(['video'])
</script>

<template>
    <AuthenticatedLayout>
        <div class="grid grid-flow-row grid-rows-max gap-4 grid-cols-3 text-center text-xl m-10">
            title is <span>{{ video.title }}</span>
        </div>
    </AuthenticatedLayout>
    
</template>