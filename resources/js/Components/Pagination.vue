<template>
    <div class="flex" style="overflow: auto;">
        <template v-for=" link in links" :key="key">  
            <div v-if="link.url === null || link.label == page " v-html="link.label" 
            class="mb-1 mr-1 md:px-4 py-3 p-4 sm:px-4 text-green-400 text-sm leading-4 border-2 rounded-full  bg-black"></div>
            <Link v-else :href="link.url" v-html="link.label" 
            class="mb-1 mr-1 md:px-4 py-3 p-4  sm:px-4 text-green-900 text-sm leading-4 border-2 rounded-full hover:bg-white bg-teal-700 ">
            </Link>
        </template>
       
    </div>
</template>


<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({
    links: [],
    page:""
})


</script>