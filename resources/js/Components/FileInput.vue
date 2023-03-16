<script setup>
import { ref } from 'vue';
import SecondaryButton from './SecondaryButton.vue';

const emit = defineEmits(['setFile']);
const props = defineProps(['accept']);

const input = ref(null);

const triggerBrowse = () => {
    input.value.click()
}

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <SecondaryButton @click="triggerBrowse" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        <input
            class="hidden"
            @input="$emit('setFile', $event.target.files[0])"
            ref="input"
            type="file"
            :accept="accept"
        />
        Browse...
    </SecondaryButton>
</template>
