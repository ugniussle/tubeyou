<script setup>

defineProps(['picture', 'size']);

</script>

<template>
    <img style="object-fit: cover;" :class="'w-'+size +' h-'+size+' rounded-full'" :src="picture">
</template>
