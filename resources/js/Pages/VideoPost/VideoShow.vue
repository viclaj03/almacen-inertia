<template>
    <AppLayout :title="video.title">
  
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          {{video.title}}
        </h2>
        <br />
        <Link :href="route('videos.create')"
          class="mt-10 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
        Nueva
        </Link>

      </template>
  
  
  
  
      <div class="">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="p-3  shadow-xl sm:rounded-lg flex mobile">
          
          <div class="mx-auto video-show ">            
                <video class="w-96" :poster="'/storage/light_versions2/' + video.image_path" controls muted >
   <source :src="'/storage/videosPost/'+ video.video_path" type="video/mp4">
   Your browser does not support the video tag.
</video>
              </div>
               
          </div>


          
       
          <div class="break-words">
            <h2>Information {{ video.title }}</h2>
            <p class="text-white">ID: <span>{{ video.id }}</span></p>
            <p class="text-white">Subido: <span>{{ video.created_at }}</span></p>
            <p class="text-white">Peso: <span>{{ video.file_size }}</span></p>
            <p v-if="video.original_url" class="text-white">Original url: <a target="_blank" :href="video.original_url"><span class="text-blue-600">{{ video.original_url }}</span></a> </p>
          </div>

          <div class="extra_information">

          </div>
          
        </div>
      </div>
    </AppLayout>
  </template>
     
    
  <script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import Pagination from '@/Components/Pagination.vue';
  import { onMounted } from 'vue'; // Importa el hook onMounted de Vue
  import { Link } from '@inertiajs/vue3';
  import { router } from '@inertiajs/vue3';
  //import axios from 'axios';
  
  

  
  
  defineProps({video: Object })
  
  
  
 
  
  </script>

  
  

<style>
.list-tags{
  min-width: 230px;
}

@media only screen and (max-width: 600px) {


  .list-tags{
  min-width: 0vh;
  }

  .mobile{
    display: flex;
    flex-direction: column
  }



  .mobile .list-tags  {
  order: 2; /* Cambia el orden del segundo elemento */
}

.mobile .video-show {
  order: 1; /* Cambia el orden del segundo elemento */
}
}



</style>