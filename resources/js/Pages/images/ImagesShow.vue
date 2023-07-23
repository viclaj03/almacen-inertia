<template>
    <AppLayout title="Dashboard">
  
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          {{image.name}}
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
  
  
  
  
      <div class="">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="p-3  shadow-xl sm:rounded-lg flex mobile">
          <div class="list-tags">
            <h3 class="text-white">Artist</h3>
                <ul v-for="tag in tag_artist">
                 <li class="text-blue-600" style="font-weight: bold;"> <a>?</a> <a>{{tag.name}}</a> {{ tag.image_posts_count }}</li>
                </ul>

                <h3 class="text-white">Copyright</h3>
                <ul v-for="tag in tag_copyright">
                 <li class="text-blue-600" style="font-weight: bold;"> <a>?</a> <a>{{tag.name}}</a> {{ tag.image_posts_count }}</li>
                </ul>

                <h3 class="text-white">Character</h3>
                <ul v-for="tag in tag_general">
                 <li class="text-blue-600" style="font-weight: bold;"> <a>?</a> <a>{{tag.name}}</a> {{ tag.image_posts_count }}</li>
                </ul>

                <h3 class="text-white">General</h3>
                <ul v-for="tag in tag_character">
                 <li class="text-blue-600" style="font-weight: bold;"> <a>?</a> <a>{{tag.name}}</a> {{ tag.image_posts_count }}</li>
                </ul>
                <h3 class="text-white">Meta</h3>
                <ul v-for="tag in tag_meta">
                 <li class="text-blue-600" style="font-weight: bold;"> <a>?</a> <a>{{tag.name}} {{ tag.image_posts_count }}</a></li>
                </ul>
            </div>
          <div class="mx-auto image-show">
                <img class="" :src="'/storage/imagesPost/'+ image.imagen" :alt="image.name">
                <div class="bg-gray-600 row-auto" >{{ image.name }} </div>
              </div>
          </div>

          <div class="break-words">
            <h2>Information</h2>
            <p class="text-white">ID: <span>{{ image.id }}</span></p>
            <p class="text-white">Subido: <span>{{ image.created_at }}</span></p>
            <p class="text-white">Peso: <span>{{ image.file_size }}</span></p>
            <p v-if="image.original_url" class="text-white">Original url: <a target="_blank" :href="image.original_url"><span class="text-blue-600">{{ image.original_url }}</span></a> </p>
            <p v-if="image.danbooru_url" class="text-white">Danbooru: <a target="_blank" :href="image.danbooru_url"> <span class="text-blue-600">{{ image.danbooru_url }}</span></a></p>

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
  
  

  
  
  defineProps({image: Object, tag_general:Array, tag_copyright:Array,tag_character:Array,tag_artist:Array,tag_meta:Array, })
  
  
  
 
  
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

.mobile .image-show {
  order: 1; /* Cambia el orden del segundo elemento */
}
}



</style>