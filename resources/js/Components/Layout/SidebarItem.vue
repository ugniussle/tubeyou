<script setup>
import { Link } from '@inertiajs/vue3';

defineProps(['link'])
</script>

<template>
    <Link :href="link" class="whitespace-nowrap">
    <div class="p-2 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out rounded-sm">
            <main class="inline">

            <slot/>
            </main>
        </div>
    </Link>
</template>