<script setup>
import { Link } from '@inertiajs/vue3';

defineProps(['user', 'size']);

</script>

<template>
    <Link :href="route('channels.view', user.id)">
        <img 
            style="object-fit: cover;" 
            :style="'width:'+size+'; height:'+size" 
            class="rounded-full inline" 
            :src="user.profile_picture">
    </Link>
</template>
