<script setup>
import { ref } from 'vue';

const emit = defineEmits(['setFile']);

const input = ref(null);

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        @input="$emit('setFile', $event.target.files[0])"
        ref="input"
        type="file"
    />
</template>
