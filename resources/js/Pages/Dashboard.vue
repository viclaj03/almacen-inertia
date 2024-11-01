
<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Escritorio
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg dar">
                 <div class="dark:text-white">
                  Numero de imagens:{{numImages}} Numero 18: {{numImages18 }} Numero Faltan : {{ imagesFaltan }}
                </div>
                  <div class=" grid grid-cols-2 sm:grid-cols-2 md:grid-cols-5 gap-4 m-1">
            <div v-for="image in images" :key="image.id" class="">
              <a :href="'storage/imagesPost/' + image.imagen" data-fancybox="gallery">
                <font-awesome-icon v-if="image.private" icon="lock" style="color: #eae43e; " class="absolute " />

                <div v-if="image.pegi_18" class="nsfw-style absolute">NSFW</div>
                
                
                <img v-if="image.light_version_imagen" class="" :src="'/storage/light_versions/' + image.light_version_imagen" :alt="image.name">
                <img v-else class="" :src="'/storage/imagesPost/' + image.imagen" :alt="image.name">              </a>
              <div class="bg-white uppercase text-center image-name-list">
               {{ image.name }}
              </div>
              <h1 class="btn  border-red-900 border-4 bg-teal-900">
                <Link :href="route('images.show', image.id)">Enlace <div v-if="image.secondary_tags">✅</div> <div v-else>❌</div> </Link>
              </h1>
              <div>
                <Link :href="route('image.addFavorite', image.id)" method="post" >Enlace </Link>
                <font-awesome-icon v-if="image.isFavorited" icon="heart" style="color: #c5dc18;" />
                <font-awesome-icon v-else icon="fa-regular fa-heart" style="color: #eb1414;" />
              </div>
              <br />
              
            </div>
          </div>
                    
                </div>
            </div>
        </div>
    </AppLayout>
</template>


<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
//import Welcome from '@/Components/Welcome.vue';
import { Fancybox } from '@fancyapps/ui';
import '@fancyapps/ui/dist/fancybox/fancybox.css';
import { onMounted } from 'vue'; // Importa el hook onMounted de Vue
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { createInertiaApp, useForm } from '@inertiajs/vue3'
import { object } from 'yup';
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






defineProps({ images: Array,tags:Array, numImages: Object, numImages18:Object, imagesFaltan:Object })




onMounted(() => {
  // Lógica que se ejecutará después de que el componente se haya montado en el DOM
  Fancybox.bind('[data-fancybox]', {
    // Opciones de configuración de FancyBox
    // Puedes personalizarlo según tus necesidades
  });
  
});

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




</style>