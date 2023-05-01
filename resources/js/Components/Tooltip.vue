<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    message: String,
})
onMounted(() => {
    console.log(props.message)
})

const tooltip = ref(null)

const showTooltip = (args) => {
    console.log(args)
    const event = args[0]
    tooltip.value.style.transform = "scale(1)"

    let yScroll = window.scrollY

    // if tooltip would go beyond screen boundries, attach it to the right
    if(window.innerWidth - event.pageX < tooltip.value.offsetWidth) {
        tooltip.value.style.right = 0 + 'px'
    } else {
        tooltip.value.style.left = event.pageX + 'px'
    }

    // if tooltip would go beyond screen boundries, attach it to the bottom
    if((window.innerHeight + yScroll) - event.pageY < tooltip.value.offsetHeight) {
        tooltip.value.style.bottom = 0 + 'px'
        tooltip.value.style.top = ''
    } else {
        tooltip.value.style.top = (event.pageY - yScroll) + 'px'
        tooltip.value.style.bottom = ''
    }

    tooltip.value.focus({focusVisible: false})
}
</script>

<template>
    <div ref="tooltip" class="fixed bg-white whitespace-nowrap transition-all border-1 border-gray-700 text-base scale-0">
        {{ message }}
    </div>

    <main >
        <div @mouseover="(e) => showTooltip([e])">
            <slot/>
        </div>
    </main>
</template>
