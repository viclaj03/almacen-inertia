<template>
  <AppLayout title="Dashboard">

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        images
      </h2>
      <br />
      <Link :href="route('images.create')"
        class="mt-10 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
      Nueva
      </Link>

      <Link :href="route('image.url')"
        class="mt-10 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
      Nueva por URL
      </Link>
    </template>




    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-3 overflow-hidden shadow-xl sm:rounded-lg ">
          <div class=" grid grid-cols-2 sm:grid-cols-2 md:grid-cols-5 gap-4 m-1">
            <div v-for="image in images.data" :key="image.id" class="">
              <a :href="'storage/imagesPost/' + image.imagen" data-fancybox="gallery">
                <img class="" :src="'storage/imagesPost/'+ image.imagen" :alt="image.name">
              </a>
            <div class="bg-white uppercase items-center">
              {{ image.name }}
            </div>
              <h1 class="btn  border-red-900 border-4 bg-teal-900"><Link :href="route('images.show',image.id)">Enlace </Link></h1>
              <br/>
              <button @click="destroy(image.id)"
        class="mt-10 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
      Eliminar 
              </button>
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
//import axios from 'axios';






async function   destroy(id) {
    if (confirm("Are you sure you want to Delete")) {

      //var l = await  axios.delete(`/images/${id}`);
      //console.log(l)
      router.delete(`/images/${id}`);
    }
}



defineProps({ images: Array })



onMounted(() => {
  // Lógica que se ejecutará después de que el componente se haya montado en el DOM
  Fancybox.bind('[data-fancybox]', {
    // Opciones de configuración de FancyBox
    // Puedes personalizarlo según tus necesidades
  });
});

</script>