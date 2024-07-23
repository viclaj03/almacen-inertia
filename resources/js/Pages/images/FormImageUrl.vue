<template>
    <AppLayout title="New Imagen">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Nueva Imagen por URL (Only danbooru) j
            </h1>
        </template>

        <div class="flex justify-center items-center p-5">
            <form @submit.prevent="submit" class="bg-gray-800" style="padding: 15px 100px;">
                <label class="uppercase text-white" for="url_search">URL</label><br/>
                <input id="url_search" v-model="form.url_search" @change="processUrl" />
                {{ form.url_search }}
            </form>
        </div>
        {{ keyDanbooru }}<-> {{ loginDanbooru }}

        <div class="py-12 col">
            <div class="grid   max-w-7xl mx-auto ">
                <div>
                    
                </div>

                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Has Children</th>
                            <th class="px-4 py-2">Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        
        
                        <tr v-for="image in images" :key="image.id">
                            
                            <td class="border px-4 py-2">{{ image.id }}</td>
                            <td class="border px-4 py-2">{{ image.has_children }}</td>
                            <td class="border px-4 py-2">
                                <img :src="image.preview_file_url" alt="Image" class="w-24 h-24 object-cover"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
   
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';

const { keyDanbooru, loginDanbooru } = defineProps(['keyDanbooru', 'loginDanbooru']);

const form = useForm({
    url_search: '',
});

const images = ref([]);
const loading = ref(false);
const error = ref(null);

const processUrl = () => {
    let url = form.url_search;
    const regex = /^https:\/\/danbooru\.donmai\.us\/posts\?tags=(.+)$/;
    const match = url.match(regex);
    const url_key = `&api_key=${keyDanbooru}&login=${loginDanbooru}`

    if (match) {
        form.url_search = `https://danbooru.donmai.us/posts.json?tags=${match[1]}${url_key}`;
    } else {
        console.error('URL no válida');
    }
};

const submit = async () => {
    processUrl();
    loading.value = true;
    error.value = null;
    try {
        const response = (await axios.get(form.url_search));
        images.value = response.data;
    } catch (err) {
        error.value = 'Error al obtener los datos';

        console.error(err);
        secondPetition()
    } finally {
        loading.value = false;
    }
};



const secondPetition= async() => {
fetch(form.url_search)
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
  })
  .then(data => {
    images.value = data
    console.log(data);
  })
  .catch(error => {
    console.error('Error:', error);
  });
}










</script>
