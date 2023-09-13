<template>
  <AppLayout :title="image.name">

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ image.name }}
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
          </div>
          <div class="mx-auto image-show">
            <video loop="loop" v-if="image.light_version_imagen" :poster="'/storage/light_versions/' + image.light_version_imagen" class="w-96" controls >
              <source :src="'/storage/imagesPost/' + image.imagen" type="video/mp4">
              Your browser does not support the video tag.
            </video>
            <img v-else class="object-fill image-size" :src="'/storage/imagesPost/' + image.imagen" :alt="image.name">
            <div class="bg-gray-600 row-auto">{{ image.name }} </div>
          </div>
        </div>

        <div class="break-words">
          <h2>Information</h2>
          <p class="text-gray-800 dark:text-gray-200">ID: <span>{{ image.id }}</span></p>
          <p class="text-gray-800 dark:text-gray-200">Subido: <span>{{ image.created_at }}</span></p>
          <p class="text-gray-800 dark:text-gray-200">Peso: <span>{{ image.file_size }}</span></p>
          <p class="text-gray-800 dark:text-gray-200">Hash: <span>{{ image.imagen_hash }}</span></p>
          <p v-if="image.original_url" class="text-gray-800 dark:text-gray-200">Original url: <a target="_blank"
              :href="image.original_url"><span class="text-blue-600">{{ image.original_url }}</span></a> </p>
          <p v-if="image.danbooru_url" class="text-gray-800 dark:text-gray-200">Danbooru: <a target="_blank" :href="image.danbooru_url"> <span
                class="text-blue-600">{{ image.danbooru_url }}</span></a></p>
          <p class="text-gray-800 dark:text-gray-200" v-if="image.private">Imagen privada</p>
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





defineProps({ image: Object, tag_general: Array, tag_copyright: Array, tag_character: Array, tag_artist: Array, tag_meta: Array, })





</script>

  
  

<style>
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

.tooltip:hover .tooltiptext {
  visibility: visible;
}</style>


