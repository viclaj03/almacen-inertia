<template>
    <AppLayout :title="!image?'Formulario Imagen':'Editando ' + image.name">
        <template #header>
            <div v-if="!image" class="flex justify-between">
            <h2  class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Nueva Imagen
            </h2>
            <div >
                <Link  :href="route('image.seeByUrl')" 
            class="  bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
            Subir por url  </Link>
            </div>
            </div>
            <div v-else >
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Editando  {{ image.id }}: {{ image.name }} 
            </h2>
            </div>
        
            
        </template>

        <div class="py-12  col   ">
            <div class="grid grid-cols-2 gap-6   max-w-7xl mx-auto sm:px-6 lg:px-12 ">
                <div>
                    <Form :validation-schema="mySchema" @submit="submitForm"
                        class="  w-full max-w-xl    border-green-400 border-2 rounded-2xl" id="form" :class="{'bg-red-600':form.pegi18,'bg-slate-600':!form.pegi18}">
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
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">URL Extra</label>
                            <Field name="danbooru_url" v-model="form.danbooru_url" type="url" id="url_danbooru"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="URL" />
                                <div v-if="form.errors.danbooru_url" class="text-green-500 text-xs italic uppercase" v-html="form.errors.danbooru_url"></div>
                            <ErrorMessage name="danbooru_url" class="text-green-500 text-xs italic uppercase" />

                        </div>

                        

                        <div class="mb-4 p-5">
                            <input type="checkbox" v-model="form.pegi18"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                >
                            <label for="disabled-checkbox" class="pr-10 ml-2 text-sm font-medium text-white">NSFW</label>
                            
                            <input type="checkbox" v-model="form.private"
                                class=" w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                >
                            <label for="disabled-checkbox" class=" ml-2 text-sm font-medium text-white">Private</label>
                        </div>

                        <div>
                            <v-select class=" " multiple v-model="form.tags" @search="onSearch" label="label" :options="searchResults"
                                placeholder="Etiquetas">
                            </v-select>

                        </div>

                        <div>
                            <AutocompleteSearch :useInput="false" v-model="form.tags_strings"/>

                        </div>

                        <div v-if="!image" class="mb-4 p-5">
                            <label for="image"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Imagen</label>
                            <Field name="image" type="file" id="imagen" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white "
                                @input="handleImageChange" @change="onFileChange"  />

                            <ErrorMessage name="image" class="text-green-500 text-xs italic uppercase" />

                            <div v-if="form.errors.image" class="text-green-500 text-xs italic uppercase" v-html="form.errors.image"></div>

                            <div v-if="form.errors.image">
                            <input type="checkbox" v-model="form.hash_ignore"
                                class=" w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                >
                            <label for="disabled-checkbox" class=" ml-2 text-sm font-medium text-white">Ignorar hash </label>
                        </div>

                        

                            <div v-if="form.image">Peso {{ form.image.size }}, Formato : {{ form.image.type }}</div> 
                        </div>
                        <div class="mb-4 " style="display: none ;">
                            <label for="description_image"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                            <Field name="description_image" v-model="form.description" as="textarea" id="description_image"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="descripción" />
                                <div v-if="form.errors.description" class="text-green-500 text-xs italic uppercase" v-html="form.errors.description"></div>
                            <ErrorMessage name="danbooru_url" class="text-green-500 text-xs italic uppercase" />

                        </div>


                        <div  class="w-full bg-gray-200 rounded-full dark:bg-gray-700" v-if="form">
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

                <div class="relative">
                    <font-awesome-icon v-if="form.private"  icon="lock" style="color: #eae43e; " class="candado-private" /> 

                    <a v-if="!image" href="https://www.w3schools.com/tags/img_girl.jpg" data-fancybox="gallery">
                        <img  src="https://www.w3schools.com/tags/img_girl.jpg" alt="image" width="500" height="600"
                            id="imagePreview">
                            
                    </a>

                    <a v-else :href="'/storage/imagesPost/' + image.imagen" data-fancybox="gallery">


                  <img v-if="image.light_version_imagen" class=""
                  :src="'/storage/light_versions/' + image.light_version_imagen" alt="image" width="500" height="600"
                            id="imagePreview"> 
                        
                            <img v-else :src="'/storage/imagesPost/' + image.imagen" alt="image" width="500" height="600"
                            id="imagePreview">
                    </a>
                    <button @click="removeURLParameters" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">limpiar url</button>
                    <div class="mb-4 ">
                            <label for="description_image"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                            <Field name="description_image" v-model="form.description" as="textarea" id="description_image"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="descripción" />
                                <div v-if="form.errors.description" class="text-green-500 text-xs italic uppercase" v-html="form.errors.description"></div>
                            <ErrorMessage name="danbooru_url" class="text-green-500 text-xs italic uppercase" />

                        </div>

                        <div v-if="image">
                            <input type="checkbox" v-model="form.getTags"
                                class=" w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                >
                            <label for="disabled-checkbox" class=" ml-2 text-sm font-medium text-white">volver a coger datos</label>
                            <div class="border-solid border-r-slate-600 bg-white">
                                <p>{{ image.secondary_tags }}</p>
                            </div>
                        </div>

                </div>
                

            </div>
        </div>

    </AppLayout>
</template>
   
  
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Fancybox } from '@fancyapps/ui';
import '@fancyapps/ui/dist/fancybox/fancybox.css';
import { VueElement, onMounted } from 'vue'; // Importa el hook onMounted de Vue
import { createInertiaApp, useForm } from '@inertiajs/vue3'
import { Form, Field, ErrorMessage } from "vee-validate";
import * as yup from 'yup';
import { Link } from '@inertiajs/vue3';
//import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
//
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
//import axios from 'axios';
/* import specific icons */
import {faLock } from '@fortawesome/free-solid-svg-icons'

/* add icons to the library */
library.add(faLock)



const { image,tag_list } = defineProps(['image','tag_list']); 


const form = useForm({
    name: image ? image.name :   "",
    original_url: image ? image.original_url :   "",
    danbooru_url: image ? image.danbooru_url :   "",
    image: image ? image:  null,
    pegi18: image ? !!image.pegi_18 : false,
    private: image ? !!image.private : false,
    tags: tag_list? tag_list : [],
    hash_ignore: false,
    description: image ? image.description : "",
    getTags: false,
    tags_strings: image ? image.secondary_tags : ''
})



const submitForm = () => {


    if(!image){
    form.post('/images',
    {
        onSuccess: () => {
    
    form.image = null
    if(true){
        form.name = ""
    form.original_url = ""
    }
    document.getElementById('imagen').value =""
    form.danbooru_url = ""
    form.hash_ignore = false
    form.description = ""
    document.getElementById('imagePreview').src = 'https://www.w3schools.com/tags/img_girl.jpg';
  },
    }
    
    );
     
    }else{
    form.put(`/images/${image.id}`);
    }


};


const removeURLParameters = (event)=> {
    
    // Obtén la URL actual
    const currentURL = form.danbooru_url;

    // Divide la URL en base a "?"
    const parts = currentURL.split("?");
    
    // Si hay al menos dos partes (la URL base y los parámetros de búsqueda), toma la primera parte
    if (parts.length > 1) {
      form.danbooru_url = parts[0];
    }
  }






const mySchema = yup.object({
    name: yup.string().required('El nombre es requerido '),
    original_url: yup.string().nullable().url('pon una url'),
    danbooru_url: yup
        .string().nullable()
        .url('Pon una URL válida')
        .test('is-danbooru-url', 'El enlace debe ser de danbooru', /*async*/  function (value) {
            if (value && value.length > 0 && !form.hash_ignore  ) {
                //return /danbooru\.donmai\.us/.test(value);
                /*try {
          // Realizar la solicitud a tu API
          
          const response = await axios.get(`/api/searchby?url=${value}`, {
            params: {
              url: value,
            },  
          })
          
          // Actuar según la respuesta de la API
          if (response.data.name ) {
            
            return this.createError({ message: `enlace repetido \n localhost/images/${response.data.id}` });
            
          } else {
            return true
          }
        } catch (error) {
          console.error('Error al hacer la solicitud a la API:', error);
          return this.createError({ message: 'Error al validar el enlace con la API' });
        }
               */ 
                
            }
            return true;
        }),
    image:   yup
        .mixed()
        .test('image', 'El archivo debe ser una Imagen', function (value) {
            // Si no se adjunta una nueva imagen y ya existe una imagen, pasa la validación sin comprobaciones adicionales.
            if (!value && image) {
                return true;
            }
           // console.log('no hay imagen')
           console.log(value)

            if(!value){
                console.log('no hay imagen')
                return false;
            }

            // Si se adjunta una nueva imagen, realiza las comprobaciones de formato del archivo.
            if ( !['image/jpg', 'image/jpeg', 'image/webp', 'image/gif', 'image/png', 'video/mp4', 'video/webm' ].includes(value.type)) {
                console.log(value)
                return false;
                
            }
            return true;
        }),
})








const handleImageChange = (event) => {
    form.clearErrors()
    var file =  event.target.files[0];
    form.image = file;
    document.getElementById('imagePreview').src = URL.createObjectURL(form.image);
    document.getElementById('imagePreview').parentElement.href = URL.createObjectURL(form.image);

    if(form.name == ""){
        var prename  = form.image.name.replace(/_/g, ' ');
        prename = prename.replace(/\..+$/, '');
        form.name = prename
    }
};










onMounted(() => {
    // 
    Fancybox.bind('[data-fancybox]', {
    });

});

</script>



<script>
import vSelect from 'vue-select';
import axios from 'axios';
import AutocompleteSearch from '@/Components/AutocompleteSearch.vue';

// ...

export default {
    // ...
    components: {
        vSelect,
    },
    data() {
        return {
            form: {
                // ...
                tag: null, // Variable para almacenar el usuario seleccionado
            },
            searchResults: [], // Lista vacía para almacenar los resultados de búsqueda
        };
    },
    methods: {
        // Método para buscar usuarios de GitHub
        onSearch(search) {
            // Realizar una solicitud a la API de GitHub para buscar usuarios
            axios.get(`/api/tags?name=${search}`)
                .then((response) => {
                    // Obtener los resultados de búsqueda de usuarios
                    const tags = response.data;
                    // Almacenar los resultados de búsqueda en searchResults
                    this.searchResults = tags.map((tag) => ({  value: tag.id ,label: (tag.name == tag.translate_esp? tag.name: (tag.name + '<->'+ tag.translate_esp)) }));
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
};



const serchImage = () => {

const selectedTags = form.tags.map((tag) => tag.value); // Obtener solo los valores de las etiquetas seleccionadas
const selectedTagsName = form.tags.map((tag) => tag.label); // Obtener solo los valores de las etiquetas seleccionadas

const selectedTagsDisable = form.tags_disable.map((tag) => tag.value); // Obtener solo los valores de las etiquetas seleccionadas
const selectedTagsNameDisable = form.tags_disable.map((tag) => tag.label); // Obtener solo los valores de las etiquetas seleccionadas

router.get('/search', { tags: selectedTags, tags_name: selectedTagsName, tags_disable: selectedTagsDisable, tags_name_disable: selectedTagsNameDisable,num:form.num,tags_strings:form.tags_strings });

};

</script>

<style>
.candado-private{
    position: absolute;
}

</style>
