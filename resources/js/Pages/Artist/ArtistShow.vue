<template>
    <AppLayout :title="artist.name">

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ artist.name }} 
                <Link  :href="route('artist.addFavorite', artist)" method="post">
                <font-awesome-icon v-if="artist.isFavorite" icon="heart" style="color: #c5dc18;" />
                <font-awesome-icon v-else icon="fa-regular fa-heart" style="color: #eb1414;" />
                </Link>
            </h2>
            <br>
            <div v-if="comment" class="dark:text-white">
            {{comment.comment }}<br>
            {{comment.last_change_date }}
        </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-3 overflow-hidden shadow-xl sm:rounded-lg ">
                    <div class="dark:text-white">
                        {{ artist.tag.wiki }}
                    </div>
                    <div class="text-blue-400" v-for="url in artist.urls ">
                        <a class="" :href="url.url">{{ url.url }}</a>
                    </div>
                </div>
                {{artist.isFavorite }}
                <div class=" grid grid-cols-2 sm:grid-cols-2 md:grid-cols-5 gap-4 m-1">
                    <div v-for="image in images" :key="image.id" class="">
                        <img v-if="image.light_version_imagen" class=""
                            :src="'/storage/light_versions/' + image.light_version_imagen" :alt="image.name">
                        <img v-else class="" :src="'/storage/imagesPost/' + image.imagen" :alt="image.name">
                        <div class="bg-white uppercase text-center ">
                            {{ image.name }}
                        </div>
                        <h1 class="btn  border-red-900 border-4 bg-teal-900">
                            <Link :href="route('images.show', image.id)">Enlace </Link>
                        </h1>

                        <div>
                <Link :href="route('image.addFavorite', image.id)" method="post" > 
                <font-awesome-icon v-if="image.isFavorited" icon="heart" style="color: #c5dc18;" />
                <font-awesome-icon v-else icon="fa-regular fa-heart" style="color: #eb1414;" />
            </Link>
              </div>

                        <br />
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


import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
//import axios from 'axios';
/* import specific icons */
import { faHeart } from '@fortawesome/free-solid-svg-icons'

import { faHeart as faRegularHeart } from '@fortawesome/free-regular-svg-icons'
import { array } from 'yup';

/* add icons to the library */
library.add(faHeart, faRegularHeart)



const props = defineProps({
    artist: {
        type: Object,
    },
    images: {
        type: Array
    },
    comment:{
        type:Object
    }
});



</script>

