<script setup>
import { onMounted } from 'vue';
import { ref } from 'vue';

const props = defineProps(['target'])

const showTooltip = ref(false)

onMounted(() => {
    if(props.target.constructor === Array) {
        for(let i = 0; i < props.length; i++) {
            target[i].addEventListener('mouseover', () => { showTooltip = true })
        }
    } else {
        target.addEventListener('mouseover', () => { showTooltip = true; console.log('works') })
    }
})
</script>

<template>
    <div v-show="showTooltip">
        <slot />
    </div>
</template>
