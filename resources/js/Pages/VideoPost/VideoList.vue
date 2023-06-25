<template>
    <AppLayout title="Dashboard">
  
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          videos
        </h2>
        <br />
        <Link :href="route('videos.create')"
          class="mt-10 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
        Nueva
        </Link>
      </template>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="p-3 overflow-hidden shadow-xl sm:rounded-lg ">
            <div class=" grid grid-cols-2 sm:grid-cols-2 md:grid-cols-5 gap-4 m-1">
              <div v-for="video in videos.data" :key="video.id" class="">
               <a data-autoplay="false" :href="'storage/videosPost/' + video.video_path" data-fancybox="video-gallery" >
                  <img  class="" :src="'storage/videosPost/'+ video.video_path + '#t=0.1'" :alt="video.name">
                </a>
                <div class="bg-white m-3">
                {{ video.title }}
                </div>
                <h1 class="btn  border-red-900 border-4 bg-teal-900"><Link :href="route('videos.show',video.id)">Enlace </Link></h1>
                <br/>
                <button @click="destroy(video.id)"
          class="mt-10 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
        Eliminar 
                </button>
              </div>
            </div>
          </div>
          <div>
            <Pagination :links="videos.links" :page="videos.current_page" />
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
  
  
  
  
  
  
   function  destroy(id) {
      if (confirm("Are you sure you want to Delete")) {
          router.delete(`/videos/${id}`);
      }
  }
  
  
  
  defineProps({ videos: Array })
  
  
  
  onMounted(() => {
    Fancybox.bind('[data-fancybox]', {
      // Opciones de configuración de FancyBox
      // Agrega la opción mute: true para reproducir en silencio
       // Reproducir en silencio
 })},);

  
  
  </script>