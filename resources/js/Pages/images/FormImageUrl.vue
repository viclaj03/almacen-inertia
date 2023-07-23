<template>
    <AppLayout title="New Imagen">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Nueva Imagen por URL
            </h1>
        </template>


        <div class="flex justify-center items-center  p-5">
                <form @submit.prevent="submit" class="bg-gray-800" style="padding:  15px 100px ;">
                    <label class=" uppercase text-white"  for="url_search">url</label><br/>
                    <input id="url_search" v-model="form.url_search"  @change="processUrl"/>
                    {{ form.url_search }}
                </form>
            </div>

        <div class="py-12  col">
            <div class="grid grid-cols-2 gap-6   max-w-7xl mx-auto sm:px-6 lg:px-12 ">
                <div>
                    <Form :validation-schema="mySchema" @submit="submitForm"
                        class="  w-full max-w-xl   bg-zinc-500 border-green-400 border-2 rounded-2xl" id="form">
                        <div class="mb-4 p-5">
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>

                            <Field name="name" v-model="form.name" type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Titulo" />
                            <ErrorMessage name="name" class="text-green-500 text-xs italic uppercase" />
                        </div>


                        <div class="mb-4 p-5">
                            <label for="url_imagen" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">URL
                                de la imagen</label>
                            <Field name="original_url" v-model="form.original_url" type="text" id="url_imagen"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="URL" />
                            <ErrorMessage name="original_url" class="text-green-500 text-xs italic uppercase" />

                        </div>



                        <div class="mb-4 p-5">
                            <label for="url_danbooru"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">URL Danborru</label>
                            <Field name="danbooru_url" v-model="form.danbooru_url" type="url" id="url_danbooru"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="URL" />
                            <ErrorMessage name="danbooru_url" class="text-green-500 text-xs italic uppercase" />

                        </div>

                        <div class="mb-4 p-5">
                            <input type="checkbox" v-model="form.pegi18"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                @input="changeColor">
                            <label for="disabled-checkbox" class="ml-2 text-sm font-medium text-white">+18</label>
                        </div>

                        <div class="mb-4 p-5">
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Imagen</label>
                            <Field name="image" type="file" id="imagen"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white "
                                @input="handleImageChange" @change="onFileChange" />

                            <ErrorMessage name="image" class="text-green-500 text-xs italic uppercase" />
                        </div>

                        <div v-if="form.image">Peso {{ form.image.type }}</div>

                        <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700" v-if="form">
                            <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                                v-if="form.progress" :style="{ width: form.progress.percentage + '%' }"> {{
                                    form.progress.percentage }} %</div>
                        </div>

                        <div class="text-center">
                            <button type="submi"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                :disabled="form.processing">Guardar</button>
                        </div>
                    </Form>
                </div>

                <div class=" bg-white">
                    <h1>Imagen</h1>
                    <a href="https://www.w3schools.com/tags/img_girl.jpg" data-fancybox="gallery">
                        <img src="https://www.w3schools.com/tags/img_girl.jpg" alt="image" width="500" height="600"
                            id="imagePreview">
                    </a>
                </div>

            </div>
        </div>


    </AppLayout>
</template>
   
  
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { onMounted } from 'vue'; // Importa el hook onMounted de Vue
import { useForm } from '@inertiajs/vue3'
import { Form, Field, ErrorMessage } from "vee-validate";

import { router } from '@inertiajs/vue3';


const processUrl = () => {
    alert(99)
    form.get(route('image.url'));
}

const form = useForm({
    url_search: "",
})







onMounted(() => {
    // Lógica que se ejecutará después de que el componente se haya montado en el DOM
    Fancybox.bind('[data-fancybox]', {
        // Opciones de configuración de FancyBox
        // Puedes personalizarlo según tus necesidades
    });
});

</script>