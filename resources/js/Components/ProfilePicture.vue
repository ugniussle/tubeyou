<script setup>

defineProps(['picture', 'size']);

</script>

<template>
    <img 
        style="object-fit: cover;" 
        :style="'width:'+size+'; height:'+size" 
        class="rounded-full" :src="picture">
</template>
