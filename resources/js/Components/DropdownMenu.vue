<script setup>
import { ref, onMounted } from 'vue';

const dropdown = ref(null)
const dropdownButton = ref(null)

const showDropdown = (args) => {
    const event = args[0]
    dropdown.value.style.transform = "scale(1)"

    let yScroll = window.scrollY

    // if dropdown would go beyond screen boundries, attach it to the right
    if(window.innerWidth - event.pageX < dropdown.value.offsetWidth) {
        dropdown.value.style.right = 0 + 'px'
    } else {
        dropdown.value.style.left = event.pageX + 'px'
    }

    // if dropdown would go beyond screen boundries, attach it to the bottom
    if((window.innerHeight + yScroll) - event.pageY < dropdown.value.offsetHeight) {
        dropdown.value.style.bottom = 0 + 'px'
        dropdown.value.style.top = ''
    } else {
        dropdown.value.style.top = (event.pageY - yScroll) + 'px'
        dropdown.value.style.bottom = ''
    }

    dropdown.value.focus({focusVisible: false})
}

onMounted(() => {
    dropdownButton.value.addEventListener('click', (event) => {
        setTimeout(showDropdown, 20, [event])
    })
    
    dropdown.value.addEventListener('focusout', () => {
        setTimeout(() => {
            dropdown.value.style.transform = "scale(0)"
        }, 100)
    })
})
</script>

<template>
    <!-- dropdown button --> 
    <div class="cursor-pointer">
        <svg ref="dropdownButton" class="h-14 w-14 p-2" viewport="0 0 40 40" color="gray"><circle cx="20" cy="10" r="2"/><circle cx="20" cy="20" r="2"/><circle cx="20" cy="30" r="2"/></svg>
    </div>
    
    <!-- dropdown -->
    <div tabindex="0" style="transform: scale(0);" ref="dropdown" class="fixed bg-white whitespace-nowrap w-48 transition-all border-2 border-gray-700">
        <main>
            <slot/>
        </main>
    </div>
</template>
dropdown