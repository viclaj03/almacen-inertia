
<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Dashboard 
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

                  <div class=" grid grid-cols-2 sm:grid-cols-2 md:grid-cols-5 gap-4 m-1">
            <div v-for="image in images" :key="image.id" class="">
              <a :href="'storage/imagesPost/' + image.imagen" data-fancybox="gallery">
                <img class="" :src="'storage/imagesPost/' + image.imagen" :alt="image.name">
              </a>
              <div class="bg-white uppercase text-center">
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






defineProps({ images: Array,tags:Array })




onMounted(() => {
  // Lógica que se ejecutará después de que el componente se haya montado en el DOM
  Fancybox.bind('[data-fancybox]', {
    // Opciones de configuración de FancyBox
    // Puedes personalizarlo según tus necesidades
  });
  
});

</script>


<style>
.tooltip {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 520px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;

  /* Position the tooltip */
  position: absolute;
  z-index: 1;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}
</style>