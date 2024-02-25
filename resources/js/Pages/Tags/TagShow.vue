<template>
    <AppLayout :title='tag.name'>

        <template #header>
            <h2 class="font-semibold text-3xl flex text-gray-800 dark:text-gray-200 leading-tight">
                {{ tag.name }}   <sup>{{ tag.image_posts_count }}</sup>
            </h2>
            <p v-if="tag.name != tag.translate_esp"  class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight ">
                {{ tag.translate_esp }}
            </p>
            <br />
            <Link :href="route('tags.edit',tag)"
                class="mt-10 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
            Editar
            </Link>
            <Link v-if="artist" :href="route('artist.show',artist.id)"
                class="mt-10 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
            ver
            </Link>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-3 overflow-hidden shadow-xl sm:rounded-lg ">
                    <div >
                        <div  class="text-block text-gray-800 dark:text-gray-200">
                        <!--{{ tag.wiki }} -->
                        <div v-html="tag.wiki "></div>
                       
                    </div>
                        <div v-if="tag.category == 3 && artist" class="list-irl">
                            
                            <ul v-for="url in urls" class="text-blue-400">
                                <li><a :href='url.url'>{{ url.url }}</a></li>
                            </ul>
                            </div>
                        <div class=" grid grid-cols-2 sm:grid-cols-2 md:grid-cols-5 gap-4 m-1">
            <div v-for="image in images" :key="image.id" class="">
                <img v-if="image.light_version_imagen" class="" :src="'/storage/light_versions/' + image.light_version_imagen" :alt="image.name">
                <img v-else class="" :src="'/storage/imagesPost/' + image.imagen" :alt="image.name">              <div class="bg-white uppercase text-center ">
                {{ image.name }}
              </div>
              <h1 class="btn  border-red-900 border-4 bg-teal-900">
                <Link :href="route('images.show', image.id)">Enlace </Link>
              </h1>
              <br />
            </div>
          </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
     
    
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
defineProps({ tag: Object,images: Array, artist:Object, urls: Array })
</script>

<style>
@media only screen and (max-width: 600px) {
    .wiki-table{
        display:none;
    }

    .translate-table{
        display: none;
    }
}

.text-block {
    white-space: pre-line; 
}

</style>