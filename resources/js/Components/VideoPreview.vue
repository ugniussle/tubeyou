<script setup>
defineProps(['video'])
</script>

<template>
    <div class="rounded-sm hover:ring-red-500 hover:ring-2 w-1/3">
        <span>{{ video.title }}</span>
    </div>
</template>