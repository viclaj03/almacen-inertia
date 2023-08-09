<template>
    <AppLayout title="Dashboard">

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Lista de Tags
            </h2>
            <br />
            <Link :href="route('tags.create')"
                class="mt-10 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
            Nueva
            </Link>
        </template>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-3 overflow-hidden shadow-xl sm:rounded-lg ">

                  <input
                                type="text"
                                v-model="search"
                                placeholder="Search..."
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-60 p-2.5"
                            />
                  <table class="table border w-full bg-white custom-table">
                        <thead>
                            <tr>
                                <th class="boder p-3">id</th>
                                <th class="border p-3">name</th>
                                <th class="border p-3 translate-table">Tradución</th>
                                <th class="border p-3  wiki-table">wiki</th>
                                <th class="border p-3">Categoria</th>
                                <th class="border p-3">Nº Posts</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class=" " v-for="tag in tags.data" :key="tag.id">
                                <td class="border p-3   " :class="categoryClass(tag.category)">{{ tag.id }} </td>
                                <td class="border p-3" :class="categoryClass(tag.category)">
                                    <Link :href="route('tags.show', tag)">{{ tag.name }}</Link>
                                </td>
                                <td class="border p-3 translate-table" :class="categoryClass(tag.category)">{{ tag.translate_esp }}</td>
                                <td class="border p-3 wiki-table" :class="categoryClass(tag.category)">{{ tag.wiki }}</td>
                                <td v-if="tag.category == 0" class="border p-3" :class="categoryClass(tag.category)">General </td>
                                <td v-if="tag.category == 1" class="border p-3" :class="categoryClass(tag.category)">Copyright </td>
                                <td v-if="tag.category == 2" class="border p-3" :class="categoryClass(tag.category)">Character </td>
                                <td v-if="tag.category == 3" class="border p-3" :class="categoryClass(tag.category)">Artist </td>
                                <td v-if="tag.category == 4" class="border p-3" :class="categoryClass(tag.category)">Meta </td>
                                <td class="border p-3" :class="categoryClass(tag.category)"> {{tag.image_posts_count }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <Pagination :links="tags.links" :page="tags.current_page" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
     
    
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import { watch } from "vue";
import {router} from '@inertiajs/vue3';
import { ref } from "vue";



const props = defineProps({
    tags: {
        type: Object,
        default: () => ({}),
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});



const  submit = () => {
    router.get('/tags',form).then(reponse => {
      tags = response;
    })
  }


  let search = ref(props.filters.search);
watch(search, (value) => {
  router.get(
        "/tags",
        { search: value },
        {
            preserveState: true,
            replace: true,
        }
    );
});






const categoryClass = (category) => {
  // Define las clases correspondientes a cada categoría
  const categoryClasses = {
    0: 'general',
    1: 'copyright',
    2: 'character',
    3: 'artist',
    4: 'meta',
  };

  // Retorna la clase correspondiente según el valor de la categoría
  return categoryClasses[category] || '';
};



</script>

<style>


.custom-table tbody tr:nth-child(odd) {
  background-color: #3f4058;
}

/* Estilos para las filas pares en el cuerpo de la tabla */
.custom-table tbody tr:nth-child(even) {
  background-color: #2c2d3f;
}


.custom-table tbody tr:hover {
  background-color: #1b2b48;
}


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

@media only screen and (max-width: 600px) {
    .wiki-table{
        display:none;
    }

    .translate-table{
        display: none;
    }
}


</style>