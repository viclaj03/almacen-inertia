<template>
  <AppLayout title="Lista de Imagenes">

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        images
      </h2>
      <br />
      <Link :href="route('images.create')"
        class="mt-10 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
      Nueva
      </Link>


    </template>




    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-3 shadow-xl sm:rounded-lg ">

          <form @submit.prevent="serchImage">
            <label for="default-search"
              class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
            <div class="relative search-space">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">

              </div>
              <v-select :filterable="false" multiple v-model="form.tags" @search="onSearch" label="label"
                :options="searchResults" placeholder="etiquetas buscar"
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></v-select>
              <br>
              <v-select :filterable="false" multiple v-model="form.tags_disable" @search="onSearch" label="label"
                :options="searchResults" placeholder="etiquetas desabilitar"
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></v-select>

              <button type="submit"
                class="text-white   bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
            </div>
          </form>
          <div class=" grid grid-cols-2 sm:grid-cols-2 md:grid-cols-5 gap-4 m-1">
            <div v-for="image in images.data" :key="image.id" class="relative ">
              <a :href="'/storage/imagesPost/' + image.imagen" data-fancybox="gallery">
                <font-awesome-icon v-if="image.private" icon="lock" style="color: #eae43e; " class="absolute right-0" />
                <div v-if="image.pegi_18" class="nsfw-style absolute">NSFW</div>
                <img v-if="image.light_version_imagen" class=""
                  :src="'/storage/light_versions/' + image.light_version_imagen" :alt="image.name">
                <img v-else class="" :src="'/storage/imagesPost/' + image.imagen" :alt="image.name" :title="image.name">
              </a>
              <div class="bg-white uppercase text-center image-name-list">
                {{ image.name }}
              </div>
              <h1 class="btn  border-red-900 border-4 bg-teal-900">
                <Link :href="route('images.show', image.id)">Enlace </Link>
              </h1>
              <div>
                <Link :href="route('image.addFavorite', image.id)" method="post" >Enlace </Link>
                <font-awesome-icon v-if="image.isFavorited" icon="heart" style="color: #c5dc18;" />
                <font-awesome-icon v-else icon="fa-regular fa-heart" style="color: #eb1414;" />
              </div>
            </div>
          </div>
        </div>
        <div>
          <Pagination :links="images.links" :page="images.current_page" />
        </div>
      </div>
    </div>
  </AppLayout>
</template>
   
  
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Fancybox } from '@fancyapps/ui';
import '@fancyapps/ui/dist/fancybox/fancybox.css';
import { onMounted } from 'vue'; // Importa el hook onMounted de Vue
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { Form, Field, ErrorMessage } from "vee-validate";
import { createInertiaApp, useForm } from '@inertiajs/vue3'
//font awesome
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
//import axios from 'axios';
/* import specific icons */
import { faLock, faHeart } from '@fortawesome/free-solid-svg-icons'

import { faHeart as faRegularHeart } from '@fortawesome/free-regular-svg-icons'

/* add icons to the library */
library.add(faLock, faHeart, faRegularHeart)


const { images, tags, tags_disable } = defineProps(['images', 'tags', 'tags_disable']);

// Usar las propiedades para inicializar form.tags
const form = useForm({
  tags: tags ? tags : [],
  tags_disable: tags_disable ? tags_disable : []   // Aquí utilizamos la propiedad tags de defineProps para inicializar form.tags
});

const serchImage = () => {
  const selectedTags = form.tags.map((tag) => tag.value); // Obtener solo los valores de las etiquetas seleccionadas
  const selectedTagsName = form.tags.map((tag) => tag.label); // Obtener solo los valores de las etiquetas seleccionadas

  const selectedTagsDisable = form.tags_disable.map((tag) => tag.value); // Obtener solo los valores de las etiquetas seleccionadas
  const selectedTagsNameDisable = form.tags_disable.map((tag) => tag.label); // Obtener solo los valores de las etiquetas seleccionadas

  router.get('/search', { tags: selectedTags, tags_name: selectedTagsName, tags_disable: selectedTagsDisable, tags_name_disable: selectedTagsNameDisable });

};


//import axios from 'axios';










//defineProps({ images: Array,tags:Array })




onMounted(() => {
  Fancybox.bind('[data-fancybox]', {
  });

});

</script>



<script>
import vSelect from 'vue-select';
import axios from 'axios';
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
        tag: [], // Variable para almacenar el usuario seleccionado
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
          // Almacenar los resultados de búsqueda en searchResults
          this.searchResults = tags.map((tag) => ({ value: tag.id, label: (tag.name == tag.translate_esp ? tag.name : (tag.name + '<->' + tag.translate_esp)) }));

        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};



</script>


<style>
.nsfw-style {
  color: red;
  border: 1px dashed red;
  font-size: 1.5vh;
}

.image-name-list {
  overflow: hidden;
  text-overflow: ellipsis;
  max-height: 7vh;
  white-space: nowrap;
}


ul#vs2__listbox {
  background: black;
}




.search-space {
  max-width: 150vh;
  padding: 0 0 5vh 0;
  display: flex;
}
</style>


<style scoped>
:deep(*) {
  --vs-controls-color: #664cc3;
  --vs-border-color: #664cc3;

  --vs-dropdown-bg: #282c34;
  --vs-dropdown-color: #cc99cd;
  --vs-dropdown-option-color: #cc99cd;

  --vs-selected-bg: #664cc3;
  --vs-selected-color: #eeeeee;

  --vs-search-input-color: #eeeeee;

  --vs-dropdown-option--active-bg: #664cc3;
  --vs-dropdown-option--active-color: #eeeeee;
}

@media only screen and (max-width: 600px) {
  .search-space {
    max-width: 100%;
    padding: 0 0 5vh 0;
    display: block;
  }

}
</style>


