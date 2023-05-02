<script setup>
import { ref } from 'vue';

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
        tooltip.value.style.left = event.pageX + 'px'
    }

    // if the tooltip would go beyond screen boundries, attach it to the bottom
    if((window.innerHeight + yScroll) - event.pageY < tooltip.value.offsetHeight) {
        tooltip.value.style.bottom = 0 + 'px'
        tooltip.value.style.top = ''
    } else {
        tooltip.value.style.top = (event.pageY - yScroll - tooltip.value.offsetHeight - 10) + 'px'
        tooltip.value.style.bottom = ''
    }
}
</script>

<template>
    <Teleport to="body">
        <div ref="tooltip" class="fixed bg-black/50 text-white whitespace-nowrap border-2 border-gray-700 text-base">
            <slot name="tooltipMessage"/>
        </div>
    </Teleport>

    <div ref="content" @mousemove="(e) => showTooltip([e])" @mouseleave="() => tooltip.style.transform = 'scale(0)'">
        <slot/>
    </div>
</template>
