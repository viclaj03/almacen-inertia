<template>
    <AppLayout title="New Imagen">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Nueva Imagen por URL (Only danbooru)  
            </h1>
        </template>

        <div class="flex justify-center items-center p-5">
            <form @submit.prevent="searchUrl" class="bg-gray-800" style="padding: 15px 100px;">
                <label class="uppercase text-white" for="url_search">URL</label><br />
                <input id="url_search" v-model="form.url_search" @change="processUrl" />


                

            </form>

        </div>

        <div class="py-12 col ">
            <div v-if="images.length" class="  grid   max-w-7xl mx-auto ">
                <input type="checkbox"   /> select All no terminado
                <table class="table-auto w-full  ">
                    <thead>
                        <tr>
                            <th class="dark:text-white px-4 py-2 id-table">ID</th>
                            <th class="dark:text-white px-4 py-2"> Private/18</th>
                            <th class="dark:text-white px-4 py-2">size</th>
                            <th class="dark:text-white px-4 py-2 id-table">Tamaños </th>
                            <th class="dark:text-white px-4 py-2">Similares</th>
                            <th class="dark:text-white px-4 py-2"><div>Otra</div> version</th>
                            <th class="dark:text-white px-4 py-2">Image</th>
                            <th class="dark:text-white px-4 py-2">Upload</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="image in images" :key="image.id" :class="{ 'bg-green-900': image.exists }">
                            
                            <td class="dark:text-white font-bold border px-4 py-2 id-table"><a target="_blank"
                                    :href="'https://danbooru.donmai.us/posts/' + image.id">{{ image.id }} </a></td>
                            <td class=" font-bold border px-4 py-2">
                                <div class="dark:text-white">
                                    <input type="checkbox" :value="image.private" v-model="image.private"
                                        class=" w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">

                                    <label for="disabled-checkbox"
                                        class=" ml-2 text-sm font-medium text-white">Private</label>
                                </div>

                                <div>
                                    <input type="checkbox" :value="image.pegi_18" v-model="image.pegi_18"
                                        class=" w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="disabled-checkbox"
                                        class=" ml-2 text-sm font-medium text-white">18</label>


                                        
                                </div>

                                <div>
                                    <input type="checkbox" :value="image.pegi_18" v-model="image.favorite"
                                        class=" w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="disabled-checkbox"
                                        class=" ml-2 text-sm font-medium text-white">favorite</label>
                                </div>

                                <div>
                                    <div class="dark:text-black ">
                                        <v-select 
                                        multiple 
                                        v-model="image.extra_tags" 
                                        @search="onSearch"
                                        :reduce="tag => tag.value" 
                                            label="label" :options="searchResults"
                                            placeholder="Etiquetas">
                                        </v-select>

                                    </div>
                                </div>
                            </td>


                            <td class="dark:text-white font-bold border px-4 py-2"> {{ formatFileSize(image.file_size)
                                }} <div class="size-not-table">{{ image.media_asset.image_width }}x{{ image.media_asset.image_height }}</div></td>
                            <td class="dark:text-white font-bold border px-4 py-2 id-table">
                                {{ image.media_asset.image_width }}x{{ image.media_asset.image_height }} </td>
                            <td class="dark:text-white font-bold border px-4 py-2" :class="{ 'bg-blue-400': image.similar_count > 0 & !image.exists }">
                                <a target="_blank" :href="route('images.uniqHash', image.imagen_hash)">
                                    {{ image.similar_count }}
                                </a>
                            </td>
                            <td class="dark:text-white font-bold border px-4 py-2"
                                :class="{ 'bg-red-600': image.has_children || image.parent_id }">

                                <a target="_blank" :href="'https://danbooru.donmai.us/posts/' + image.id">

                                    {{ image.has_children || !!image.parent_id }} </a>
                            </td>
                            <td class="border px-4 py-2  tooltip " :class="{ 'bg-red-800': image.is_deleted }" >
                                <div class=""> 
                                    <span class="tooltiptext overflow-scroll" >
                                        <h6 class="uppercase">etiquetas  </h6>
                                        <div class="dark:bg-gray-400 bg-white text-black">
                                            <div class="artist"> {{ image.tag_string_artist }} </div>
                                            <div class="copyright">{{ image.tag_string_copyright }}</div>
                                            <div class="character">{{ image.tag_string_character }} </div>
                                            <div class="general">{{ image.tag_string_general }} </div>
                                            <div class="meta">{{ image.tag_string_meta }} </div>
                                        </div>
                                    </span>
                                </div>


                                <a :href="image.file_url" data-fancybox="gallery" loop>
                                    <img :src="image.large_file_url" alt="Image" class="w-80   object-cover" />
                                </a>
                              <!----  <pre>
                                {{image }}</pre> -->
                            </td>
                            <td class="dark:text-white font-bold border px-4 py-2"> <input type="checkbox"
                                    :disabled="image.exists" :value="image" v-model="form.selectedImages">

                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center">
                <button @click="uploadSelected" class="mt-4 p-2 bg-blue-500 text-white   ">Submit</button>
                </div>
                
            </div>
            <div class="border-blue-800 border-solid border-4 bg-black text-white    right-10 mr-10  bottom-10 rounded-lg p-2 sticky w-20">
                {{form.selectedImages.length}} /{{images.filter(image => !image.exists).length}}/{{images.length }}
            </div>
        </div>

    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

import { Fancybox } from '@fancyapps/ui';
import '@fancyapps/ui/dist/fancybox/fancybox.css';
import { Toolbar } from "@fancyapps/ui/dist/panzoom/panzoom.toolbar.esm.js";
import "@fancyapps/ui/dist/panzoom/panzoom.toolbar.css";

const { images, url_search } = defineProps(['images', 'url_search']);

const form = useForm({
    url_search: url_search ?? '',
    selectedImages: []
});

//const images = ref([]);
const loading = ref(false);
const error = ref(null);





const searchUrl = () => {

    router.get('/upload-url', { url_search: form.url_search });

};


const uploadSelected = () => {

    form.post('/upload-url',{ 
        selectedImages: form.selectedImages,
        onSuccess: () => {
            form.reset()
        } 
    });

};



function formatFileSize(size) {
    const units = ['B', 'KB', 'MB', 'GB', 'TB'];
    let unitIndex = 0;

    while (size >= 1024 && unitIndex < units.length - 1) {
        size /= 1024;
        unitIndex++;
    }

    return `${size.toFixed(2)} ${units[unitIndex]}`;
}



onMounted(() => {
    Fancybox.bind('[data-fancybox]', {
        Toolbar: {
            enabled: true,
            display: {
                left: ["infobar"],
                middle: [
                    "zoomIn",
                    "zoomOut",
                    "toggle1to1",
                    "rotateCCW",
                    "rotateCW",
                    "flipX",
                    "flipY",
                    "fullscreen"
                ],
                right: ["slideshow", "thumbs", "close"],
            },
        },
    });


});


</script>



<script>
import vSelect from 'vue-select';
import axios from 'axios';
import AutocompleteSearch from '@/Components/AutocompleteSearch.vue';
import 'vue-select/dist/vue-select.css';

// ...

export default {
    // ...
    components: {
        vSelect,
    },
    data() {
        return {
            form: {
                // ...
                tag: null, // Variable para almacenar el usuario seleccionado
            },
            searchResults: [], // Lista vacía para almacenar los resultados de búsqueda
        };
    },
    methods: {
        // Método para buscar usuarios de GitHub
        onSearch(search) {
            // Realizar una solicitud a la API de GitHub para buscar usuarios
            axios.get(`/api/tags?name=${search}`)
                .then((response) => {
                    // Obtener los resultados de búsqueda de usuarios
                    const tags = response.data;
                    const newTags = tags.map((tag) => ({
                        value: tag.id, // ID de la etiqueta
                        label: tag.name // Nombre de la etiqueta
                    }));


                    const existingTagValues = new Set(this.searchResults.map(tag => tag.value));
                    const uniqueNewTags = newTags.filter(tag => !existingTagValues.has(tag.value));


                    // Almacenar los resultados de búsqueda en searchResults
                    this.searchResults = [
                        ...this.searchResults, 
                        ...uniqueNewTags
                    
                    ];
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
};



</script>

<style scoped>

.size-not-table{
        display: none;
    }


@media only screen and (max-width: 820px) {
    .wiki-table {
        display: none;
    }

    .id-table {
        display: none;
    }


    .size-not-table{
        display: block;
    }

}


@media only screen and (max-width: 600px) {

    .tooltip .tooltiptext {
        display: none !important;

    }
}




.tooltiptext {
    visibility: hidden;
    width: 340px;
    height: 180px;
    background-color: black;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;
    border: 3px solid black;


    /* Position the tooltip */
    position: absolute;
    z-index: 1;
    font-size: 12px;
    right: 128%;
    bottom: auto;
    right: 25%;



}

/*collapse*/
.tooltip:hover .tooltiptext {
    visibility: visible;

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

.model {
    color: #900e94;
}
</style>