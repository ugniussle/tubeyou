<script setup>
import SelectOption from './SelectOption.vue';
import { onMounted, ref } from 'vue';

defineProps(['modelValue', 'options']);

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <select
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    >
        <SelectOption v-for="option in options" :option="option"/>
    </select>
</template>
