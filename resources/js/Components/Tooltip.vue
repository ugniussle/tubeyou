<script setup>
import { onMounted } from 'vue';
import { ref } from 'vue';

defineProps(['teleportLocation'])

const mounted = ref(false)

const tooltip = ref(null)
const content = ref(null)

const showTooltip = (args) => {
    const event = args[0]
    tooltip.value.style.transform = "scale(1)"

    let yScroll = window.scrollY

    // if the tooltip would go beyond screen boundries, attach it to the right
    if(window.innerWidth - event.pageX < tooltip.value.offsetWidth) {
        tooltip.value.style.right = 0 + 'px'
    } else {
        tooltip.value.style.left = event.pageX + 15 + 'px'
        tooltip.value.style.right = ''
    }

    // if the tooltip would go beyond screen boundries, attach it to the bottom
    if((window.innerHeight + yScroll) - event.pageY < tooltip.value.offsetHeight) {
        tooltip.value.style.bottom = 0 + 'px'
        tooltip.value.style.top = ''
    } else {
        tooltip.value.style.top = (event.pageY - yScroll) - 20 + 'px'
        tooltip.value.style.bottom = ''
    }
}

onMounted(() => {
    mounted.value = true
})
</script>

<template>
    <Teleport v-if="mounted" :disabled="teleportLocation == null" :to="teleportLocation">
        <div ref="tooltip" class="z-50 fixed bg-black/50 text-white whitespace-nowrap border-2 border-gray-700 text-base scale-0">
            <slot name="tooltipMessage"/>
        </div>
    </Teleport>

    <div ref="content" @mousemove="(e) => showTooltip([e])" @mouseleave="() => tooltip.style.transform = 'scale(0)'">
        <slot/>
    </div>
</template>
