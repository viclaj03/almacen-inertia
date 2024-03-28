<template>
    <AppLayout title="Artistas">

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Lista de Artistas
            </h2>
            <br>
        </template>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-3 overflow-hidden shadow-xl sm:rounded-lg ">
                  <div class="flex  justify-between" preser>
                  <input preserve-state 
                                type="text"
                                v-model="search"
                                placeholder="Search..."
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-60 p-2.5"
                            />
                            <label class="relative inline-flex  mr-5 cursor-pointer">
  <input type="checkbox"   :value="1"   v-model="favoritos" class="sr-only peer" >
  <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-focus:ring-4 peer-focus:ring-yellow-300 dark:peer-focus:ring-yellow-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-yellow-400"></div>
  <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Favoritos </span>
</label>
                            
                            </div>
                  <table class="table border w-full bg-white custom-table">
                        <thead>
                            <tr>
                                <th class="boder p-3">id</th>
                                <th class="border p-3">name</th>
                                <th class="border p-3 translate-table">Nº</th>
                                <th class="border p-3">Favorito</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class=" " v-for="artist in artists.data" :key="artist.id">
                              <td class="border p-3   " >{{ artist.id }} </td>
                                <td class="border p-3" >
                                    <Link :href="route('artist.show', artist)">{{ artist.name }}</Link>
                                </td>
                                <td class="border p-3 translate-table" >{{ artist.tag.image_posts_count }}</td>
                                <td class="border p-3" >
                                  <Link  :href="route('artist.addFavorite', artist)" method="post">
                                    <font-awesome-icon v-if="artist.isFavorite" icon="heart" style="color: #c5dc18;" />
                                    <font-awesome-icon v-else icon="fa-regular fa-heart" style="color: #eb1414;" />
                                </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="bg-white">
              </div>
                <div>
                    <Pagination :links="artists.links" :page="artists.current_page" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
     
    
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import { watch } from "vue";
import {router} from '@inertiajs/vue3';
import { ref } from "vue";

import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
//import axios from 'axios';
/* import specific icons */
import {  faHeart } from '@fortawesome/free-solid-svg-icons'

import { faHeart as faRegularHeart } from '@fortawesome/free-regular-svg-icons'
import { bool, boolean } from 'yup';

/* add icons to the library */
library.add( faHeart, faRegularHeart)



const props = defineProps({
  artists: {
        type: Object,
       // default: () => ({}),
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});




  let search =  ref(props.filters.search);
  let  favoritos = ref(props.filters.favoritos); 
watch([search,favoritos], ([search,favoritos])  => {
  router.get(
        "/artist",
        { search: search,favoritos: favoritos },
        
        {
         
            preserveState: true,
            replace: false,
        }
    );
});










</script>

<style>


.custom-table tbody tr:nth-child(odd) {
  background-color: #3f4058;
}

/* Estilos para las filas pares en el cuerpo de la tabla */
.custom-table tbody tr:nth-child(even) {
  background-color: #2c2d3f;
}


.custom-table tbody tr:hover {
  background-color: #1b2b48;
}


.general {
  color: blue;
}

.copyright {
  color: rgb(156, 14, 192);
}

.character {
  color: rgb(111, 255, 0);
}


.meta {
  color: rgb(234, 255, 0);
}


.artist {
  color: rgb(255, 0, 25);
}

@media only screen and (max-width: 600px) {
    .wiki-table{
        display:none;
    }

    .translate-table{
        display: none;
    }
}


</style>