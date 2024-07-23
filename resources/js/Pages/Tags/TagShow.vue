<template>
    <AppLayout :title='tag.name'>

        <template #header>
            <h2  class="font-semibold text-3xl flex text-gray-800 dark:text-gray-200 leading-tight"  :class="categoryClass(tag.category)">
                {{ tag.name }}   <sup>{{ tag.image_posts_count }}</sup>
            </h2>
            <p v-if="tag.name != tag.translate_esp"  class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight ">
                {{ tag.translate_esp }}
            </p>
            <br />
            <div class="flex justify-around place-items-center">
            <Link :href="route('tags.edit',tag)"
                class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
            Editar
            </Link>
            <Link v-if="artist" :href="route('artist.show',artist.id)"
                class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
            ver
            </Link>

            <button  @click="destroy(tag)"
                class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
            eliminar
            </button>
          </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-3 overflow-hidden shadow-xl sm:rounded-lg ">
                    <div >
                        <div  class="text-block text-gray-800 dark:text-gray-200">
                        <!--{{ tag.wiki }} -->
                        <div v-html="tag.wiki "></div>
                       
                    </div> 
                        <div v-if="tag.category == 3 && artist" class="list-irl">
                            
                            <ul v-for="url in urls" class="text-blue-400">
                                <li><a :href='url.url'>{{ url.url }}</a></li>
                            </ul>
                        </div>

                        <div v-else-if="tag.category == 5">
                            <ul v-for="url in urls" class="text-blue-400">
                                <li><a :href='url'>{{ url }}</a></li>
                            </ul>
                        </div>
                        <div class=" grid grid-cols-2 sm:grid-cols-2 md:grid-cols-5 gap-4 m-1">
            <div v-for="image in images" :key="image.id" class="">
                <img v-if="image.light_version_imagen" class="" :src="'/storage/light_versions/' + image.light_version_imagen" :alt="image.name">
                <img v-else class="" :src="'/storage/imagesPost/' + image.imagen" :alt="image.name">              <div class="bg-white uppercase text-center ">
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
        </div>
    </AppLayout>
</template>
     
    
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
defineProps({ tag: Object,images: Array, artist:Object, urls: Array })

const categoryClass = (category) => {
  // Define las clases correspondientes a cada categoría
  const categoryClasses = {
    0: 'general',
    1: 'copyright',
    2: 'character',
    3: 'artist',
    4: 'meta',
    5:'model'
  };

  // Retorna la clase correspondiente según el valor de la categoría
  return categoryClasses[category] || '';
};


async function destroy(tag) {
  if (confirm("Eliminar imagen " + tag.name)) {
   
    router.delete(`/tags/${tag.id}`);
  }
}


</script>

<style>


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




@media only screen and (max-width: 600px) {
    .wiki-table{
        display:none;
    }

    .translate-table{
        display: none;
    }
}

.text-block {
    white-space: pre-line; 
}

</style>