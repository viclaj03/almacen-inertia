<template>
    <AppLayout title="Formulario Imagen">
        <template #header>
            <h2 v-if="!image" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Nueva Imagen 
            </h2>
            <h2 v-else class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Editando {{ image.name }} {{ image.id }}
            </h2>
            
        </template>

        <div class="py-12  col   ">
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
                                <div v-if="form.errors.danbooru_url" class="text-green-500 text-xs italic uppercase" v-html="form.errors.danbooru_url"></div>
                            <ErrorMessage name="danbooru_url" class="text-green-500 text-xs italic uppercase" />

                        </div>

                        <div class="mb-4 p-5">
                            <input type="checkbox" v-model="form.pegi18"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                @input="changeColor">
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

                <div class="">
                    <a v-if="!image" href="https://www.w3schools.com/tags/img_girl.jpg" data-fancybox="gallery">
                        <img  src="https://www.w3schools.com/tags/img_girl.jpg" alt="image" width="500" height="600"
                            id="imagePreview">
                            
                    </a>

                    <a v-else :href="'/storage/imagesPost/' + image.imagen" data-fancybox="gallery">
                        
                            <img :src="'/storage/imagesPost/' + image.imagen" alt="image" width="500" height="600"
                            id="imagePreview">
                    </a>
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
//import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';



const { image,tag_list } = defineProps(['image','tag_list']); 


const form = useForm({
    name: image ? image.name :   "",
    original_url: image ? image.original_url :   "",
    danbooru_url: image ? image.danbooru_url :   "",
    image: image ? image:  null,
    pegi18: image ? !!image.pegi_18 : false,
    private: image ? !!image.private : false,
    tags: tag_list? tag_list : [],
    hash_ignore: false
})



const submitForm = () => {


    if(!image){
    form.post('/images',
    {
        onSuccess: () => {
    
    form.image = null
    if(confirm('Subido:clear form?')){
        form.name = ""
    form.original_url = ""
    }
    form.danbooru_url = ""
    document.getElementById('imagePreview').src = 'https://www.w3schools.com/tags/img_girl.jpg';
  },
    }
    
    );
     
    }else{
    form.put(`/images/${image.id}`);
    }


};






const mySchema = yup.object({
    name: yup.string().required('El nombre es requerido capullo'),
    original_url: yup.string().nullable().url('pon una url'),
    danbooru_url: yup
        .string().nullable()
        .url('Pon una URL válida')
        .test('is-danbooru-url', 'El enlace debe ser de danbooru', function (value) {
            if (value && value.length > 0  ) {
                
                //return /danbooru\.donmai\.us/.test(value);
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
            if ( !['image/jpg', 'image/jpeg', 'image/webp', 'image/gif', 'image/png', 'video/mp4','image/avif'].includes(value.type)) {
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


const changeColor = () => {

    if (!form.pegi18) {
        document.getElementById('form').classList.add("bg-red-900")
        document.getElementById('form').classList.remove("bg-zinc-500")

    } else {
        document.getElementById('form').classList.remove("bg-red-900")
        document.getElementById('form').classList.add("bg-zinc-500")
    }
}








onMounted(() => {
    // Lógica que se ejecutará después de que el componente se haya montado en el DOM
    Fancybox.bind('[data-fancybox]', {
    });

});

</script>



<script>
import vSelect from 'vue-select';
import axios from 'axios';

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



</script>


