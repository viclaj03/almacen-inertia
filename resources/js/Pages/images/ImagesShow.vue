<template>
  <AppLayout :title="image.name">

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ image.name }} <sup v-if="image.pegi_18" class="nsfw-style">NSFW</sup> <font-awesome-icon v-if="image.private" icon="lock" style="color: #eae43e;" /> 
      </h2>
      <br />
      <Link :href="route('images.edit', image)"
        class="mt-10 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
      Editar
      </Link>
    </template>




    <div class="">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="p-3  shadow-xl sm:rounded-lg flex mobile">
          <div class="list-tags">
            <h3 class="text-gray-800 dark:text-gray-200">Artist</h3>

            <ul v-for="tag in tag_artist">

              <li class="text-blue-600 tooltip" style="font-weight: bold;">
                <Link :href="route('tags.show', tag)">?  </Link>
                <Link :href="route('image.search', { tags: [tag.id], tags_name: [tag.name] })"> {{ tag.name }} </Link> {{
                  tag.image_posts_count }}  
                  <Link  :href="route('artist.addFavorite', tag.artist_id)" method="post">
                  <font-awesome-icon v-if="tag.isFavorite" icon="heart" style="color: #c5dc18;" /> 
                  <font-awesome-icon v-else icon="heart" style="color: #000000;" /> 
                  </Link>
                  <span class="tooltiptext">

                  <h6>{{ tag.name }} {{tag.name != tag.translate_esp ? (' <-> ' + tag.translate_esp): ''   }} </h6>
                  <div class="bg-white text-black">
                    {{ tag.wiki }}
                  </div>
                </span>
              </li>
            </ul>

            <h3 class="text-gray-800 dark:text-gray-200">Copyright</h3>
            <ul v-for="tag in tag_copyright">
              <li class="text-blue-600 tooltip" style="font-weight: bold;">
                <Link :href="route('tags.show', tag)">?</Link>
                <Link :href="route('image.search', { tags: [tag.id], tags_name: [tag.name] })"> {{ tag.name }}</Link> {{
                  tag.image_posts_count }} <span class="tooltiptext">

                  <h6>{{ tag.name }} </h6>
                  <div class="bg-white text-black">
                    {{ tag.wiki }}
                  </div>
                </span>
              </li>
            </ul>

            <h3 class="text-gray-800 dark:text-gray-200">Character</h3>
            <ul v-for="tag in tag_character">
              <li class="text-blue-600 tooltip" style="font-weight: bold;">
                <Link :href="route('tags.show', tag)">?</Link>
                <Link :href="route('image.search', { tags: [tag.id], tags_name: [tag.name] })"> {{ tag.name }}</Link> {{
                  tag.image_posts_count }} <span class="tooltiptext">

                  <h6>{{ tag.name }} </h6>
                  <div class="bg-white text-black">
                    {{ tag.wiki }}
                  </div>
                </span>
              </li>
            </ul>

            <h3 class="text-gray-800 dark:text-gray-200">General</h3>
            <ul v-for="tag in tag_general">
              <li class="text-blue-600 tooltip" style="font-weight: bold;">
                <Link :href="route('tags.show', tag)">?</Link>
                <Link :href="route('image.search', { tags: [tag.id], tags_name: [tag.name] })"> {{ tag.name }} </Link> {{
                  tag.image_posts_count }} <span class="tooltiptext">

                  <h6>{{ tag.name }} {{ tag.id }} </h6>
                  <div class="bg-white text-black">
                    {{ tag.wiki }}
                  </div>
                </span>
              </li>
            </ul>
            <h3 class="text-gray-800 dark:text-gray-200">Meta</h3>
            <ul v-for="tag in tag_meta">
              <li class="text-blue-600 tooltip" style="font-weight: bold;">
                <Link :href="route('tags.show', tag)">?</Link>
                <Link :href="route('image.search', { tags: [tag.id], tags_name: [tag.name] })"> {{ tag.name }}</Link> {{
                  tag.image_posts_count }} <span class="tooltiptext">

                  <h6>{{ tag.name }} </h6>
                  <div class="bg-white text-black">
                    {{ tag.wiki }}
                  </div>
                </span>
              </li>
            </ul>
            <div v-if="image.secondary_tags">
             

            
            <div class="text-white">---Secondary--</div>
            <div v-if="tag_string_artist.length">
            <h3 class="text-gray-800 dark:text-gray-200">Artist</h3>
            
            <ul v-for="tag in tag_string_artist">
              <li class="text-blue-600 tooltip" style="font-weight: bold;">
                
                <Link :href="route('image.search', { tags_strings: tag.name.replace(/ /g, '_') })"> {{ tag.name }}</Link> {{
                  tag.post_count }}
                  
              </li>
            </ul>
          </div>

          <div v-if="tag_string_modelo.length">
            <h3 class="text-gray-800 dark:text-gray-200">modelo</h3>
            
            <ul v-for="tag in tag_string_modelo">
              <li class="text-blue-600 tooltip" style="font-weight: bold;">
                
                <Link :href="route('image.search', { tags_strings: tag.name.replace(/ /g, '_') })"> {{ tag.name }}</Link> {{
                  tag.post_count }} 
              </li>
            </ul>
          </div>

            <h3 class="text-gray-800 dark:text-gray-200">Copyrights</h3>
            <ul v-for="tag in tag_string_copyright">
              <li class="text-blue-600 tooltip" style="font-weight: bold;">
                
                <Link :href="route('image.search', { tags_strings: tag.name.replace(/ /g, '_') })"> {{ tag.name  }}</Link> {{
                  tag.post_count }} 
              </li>
            </ul>

            <h3 class="text-gray-800 dark:text-gray-200">Characters</h3>
            <ul v-for="tag in tag_string_character">
              <li class="text-blue-600 tooltip" style="font-weight: bold;">
                
                <Link :href="route('image.search', { tags_strings: tag.name.replace(/ /g, '_') })"> {{ tag.name  }}</Link> {{
                  tag.post_count }} 
              </li>
            </ul>

            


            <h3 class="text-gray-800 dark:text-gray-200">General</h3>
            <ul v-for="tag in tag_string_general">
              <li class="text-blue-600 tooltip" style="font-weight: bold;">
                
                <Link :href="route('image.search', { tags_strings: tag.name.replace(/ /g, '_') })"> {{ tag.name  }}</Link> {{
                  tag.post_count }} 
              </li>
            </ul>
            <h3 class="text-gray-800 dark:text-gray-200">Meta</h3>
            <ul v-for="tag in tag_string_meta">
              <li class="text-blue-600 tooltip" style="font-weight: bold;">
                <Link :href="route('image.search', { tags_strings: tag.name.replace(/ /g, '_') })"> {{ tag.name  }}</Link> {{
                  tag.post_count }} 
              </li>
            </ul>

            <h3 class="text-gray-800 dark:text-gray-200">Desconocidos</h3>
            <ul v-for="tag in tag_string_unknow">
              <li class="text-blue-600 tooltip" style="font-weight: bold;">
                
                <Link :href="route('image.search', { tags_strings: tag })"> {{ tag }}</Link> 
              </li>
            </ul>
          </div>
          <div v-else>
            <p class="text-white">Sin etiquetas</p>
          </div>

          </div>
          <div class="mx-auto image-show">
            
            <video loop="loop" v-if="image.light_version_imagen && image.file_ext =='mp4'" :poster="'/storage/light_versions/' + image.light_version_imagen" class="w-96" controls >
              <source :src="'/storage/imagesPost/' + image.imagen" type="video/mp4">
              Your browser does not support the video tag.
            </video>
            <img v-else class="h-auto max-w-full" :src="'/storage/imagesPost/' + image.imagen" :alt="image.name">
            <div class="bg-gray-600 row-auto">{{ image.name }} </div>
            <div>
                <Link :href="route('image.addFavorite', image.id)" method="post" >Enlace </Link>
                <font-awesome-icon v-if="image.isFavorited" icon="heart" style="color: #c5dc18;" />
                <font-awesome-icon v-else icon="fa-regular fa-heart" style="color: #eb1414;" />
              </div>
              <div class="border bg-rose-600 text-white">
          {{image.description }}
        </div>
        
            <div><button @click="destroy(image)"
                class="mt-10 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                Eliminar
              </button></div>
          </div>
        </div>

        

        <div class="break-words">
          <h2>Information</h2>
          <p class="text-gray-800 dark:text-gray-200">ID: <span>{{ image.id }}</span></p>
          <p class="text-gray-800 dark:text-gray-200">Subido: <span>{{ image.created_at }}</span></p>
          <p class="text-gray-800 dark:text-gray-200">actulizado: <span>{{ image.updated_at }}</span></p>
          <p class="text-gray-800 dark:text-gray-200">Peso: <span>{{ image.file_size }}</span></p>
          <p class="text-gray-800 dark:text-gray-200">Hash: <span>{{ image.imagen_hash }}</span></p>
          <p class="text-gray-800 dark:text-gray-200">md5:<span>{{ image.md5_hash }}</span></p>
          <p v-if="image.original_url" class="text-gray-800 dark:text-gray-200">Original url: <a target="_blank"
              :href="image.original_url"><span class="text-blue-600">{{ image.original_url }}</span></a> </p>
          <p v-if="image.danbooru_url" class="text-gray-800 dark:text-gray-200">Danbooru: <a target="_blank" :href="image.danbooru_url"> <span
                class="text-blue-600">{{ image.danbooru_url }}</span></a></p>
          <p class="text-gray-800 dark:text-gray-200" v-if="image.private">Imagen privada</p>
        </div>
 
        
      </div>
    </div>
  </AppLayout>
</template>
     
    
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { onMounted } from 'vue'; // Importa el hook onMounted de Vue
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
//import axios from 'axios';
/* import specific icons */
import { faLock, faHeart } from '@fortawesome/free-solid-svg-icons'

import { faHeart as faRegularHeart } from '@fortawesome/free-regular-svg-icons'

/* add icons to the library */
library.add(faLock, faHeart, faRegularHeart)





defineProps({ image: Object,tag_string_modelo:Array ,tag_general: Array, tag_copyright: Array, tag_character: Array, tag_artist: Array, tag_meta: Array, 
  tag_string_unknow:Array,tag_string_general:Array,tag_string_copyright:Array,tag_string_character:Array,tag_string_artist:Array,tag_string_meta:Array })


async function destroy(image) {
  if (confirm("Eliminar imagen " + image.name)) {
   
    router.delete(`/images/${image.id}`);
  }
}


</script>

  
  

<style scoped>
.nsfw-style{
  color: red;
  border: 1px dashed red;
  font-size: 1.5vh;
}
.list-tags {
  min-width: 230px;
}

.image-size {
  max-width: 100vh;
}

@media only screen and (max-width: 600px) {

  .tooltip .tooltiptext {
    display: none !important;
  }

  .list-tags {
    min-width: 0vh;
  }

  .mobile {
    display: flex;
    flex-direction: column
  }



  .mobile .list-tags {
    order: 2;
    /* Cambia el orden del segundo elemento */
  }



  .mobile .image-show {
    order: 1;
    /* Cambia el orden del segundo elemento */
  }

  .image-size {
    max-width: 100%;
  }
}



/*toop*/


.tooltip {
  position: relative;
  display: inline-block;
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
/*collapse*/
.tooltip:hover .tooltiptext {
  visibility: visible;
}







</style>





