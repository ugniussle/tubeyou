<script setup>

defineProps(['option']);

</script>

<template>
    <option
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="option"
    >{{ option.charAt(0).toUpperCase() + option.slice(1) }}</option>

</template>
