<template>
    <AppLayout title="Busqueda Inversa">
        <template #header>
            
            <div >
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                   Busqueda Inversa
                </h2>
            </div>


        </template>

        <div class="py-12  col   ">
            <div class="grid grid-cols-2 gap-6   max-w-7xl mx-auto sm:px-6 lg:px-12 ">
                <div>
                    <Form :validation-schema="mySchema" @submit="submitForm"
                        class="  w-full max-w-xl    border-green-400 border-2 rounded-2xl bg-slate-600" id="form"
                        >
                       

                        <div class="mb-4 p-5">
                            <label for="url_imagen"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">URL
                                de la imagen</label>
                            <Field name="url" v-model="form.url" type="text" id="url_imagen"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="URL" />
                            <ErrorMessage name="url" class="text-green-500 text-xs italic uppercase" />

                        </div>

                        <div class="mb-4 p-5">
                            <label for="url_imagen"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">MD5:</label>
                            <Field name="md5" v-model="form.hash" type="text" id="url_imagen"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="URL" />
                            <ErrorMessage name="md5" class="text-green-500 text-xs italic uppercase" />

                        </div>

                        

                        

                        

                        <div v-if="!image" class="mb-4 p-5">
                            <label for="image"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Imagen</label>
                            <Field name="image" type="file" id="imagen"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white "
                                @input="handleImageChange" @change="onFileChange" />

                                



                            <ErrorMessage name="image" class="text-green-500 text-xs italic uppercase" />

                            <div v-if="form.errors.image" class="text-green-500 text-xs italic uppercase"
                                v-html="form.errors.image"></div>


                        </div>


                        <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700" v-if="form">
                            <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                                v-if="form.progress" :style="{ width: form.progress.percentage + '%' }">
                                {{ form.progress.percentage }} %
                            </div>
                        </div>
                        <br>
                        

                        <div class="text-center">
                            <button type="submi"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                :disabled="form.processing">Buscar</button>
                        </div>
                    </Form>
                </div>

                <div class="relative">
                    

                        <img src="https://www.w3schools.com/tags/img_girl.jpg" alt="image" width="500" height="600"
                            id="imagePreview">
                </div>


            </div>
        </div>

        <div class="grid gap-4 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
    <!-- Usa el componente <Link> de Inertia o un <a> simple -->
    <a
      v-for="img in imageResults"
      :key="img.id"
      :href="route('images.show', img.id)"     
      target="_blank"
      rel="noopener noreferrer"
      class="block group"
    >
  
      <!-- miniatura ligera -->
     <img
        :src="img.imagen
                ? `/storage/imagesPost/${img.imagen}`
                : `/storage/light_versions/'${img.light_version_imagen}`"
        :alt="img.description || `Imagen ${img.id}`"
        class="w-full h-auto object-cover rounded-lg shadow-md transition
               group-hover:scale-105 group-hover:shadow-lg"
      /> 
    </a>
  </div>

    </AppLayout>
</template>


<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';

import AutocompleteSearch from '@/Components/AutocompleteSearch.vue';

import { Fancybox } from '@fancyapps/ui';
import '@fancyapps/ui/dist/fancybox/fancybox.css';
import { onMounted } from 'vue'; // Importa el hook onMounted de Vue
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { Form, Field, ErrorMessage } from "vee-validate";
import { createInertiaApp, useForm } from '@inertiajs/vue3'


const { imageResults,hash,url,image  } = defineProps(['imageResults','hash','url','image']);



const form = useForm({
   hash: hash? hash:'',
   url:url ? url:'',
   image: image ? image : null,

})


const submitForm = () => {
  form.post('/reverse-search', {
    forceFormData: true, // <-- necesario para que envíe archivos
    onSuccess: () => {
      console.log('Formulario enviado correctamente');
    },
  });
};

















const handleImageChange = (event) => {
    form.clearErrors()
    var file = event.target.files[0];
    form.image = file;
    document.getElementById('imagePreview').src = URL.createObjectURL(form.image);
    document.getElementById('imagePreview').parentElement.href = URL.createObjectURL(form.image);

    if (form.name == "") {
        var prename = form.image.name.replace(/_/g, ' ');
        prename = prename.replace(/\..+$/, '');
        form.name = prename
    }
};











</script>





<style>
.candado-private {
    position: absolute;
}
</style>
