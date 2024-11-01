<template>
    <AppLayout title="New tag">
        <template #header>
            <h2 v-if="tag" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight ">
                Editar:{{ tag.name }} -> {{ tag.id }} {{ tag.category }}
            </h2>
            <h2 v-else class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tigh">
                Crear Tags {{ tag }}
            </h2>
        </template>

        <div class="py-12  col    ">
            <div class="grid     mx-auto  ">
                <div class="block " style="
    text-align: -webkit-center;
">

                    <Form :validation-schema="mySchema" @submit="submitForm"
                        class="  w-full max-w-xl   bg-zinc-500 border-green-400 border-2 rounded-2xl"
                        :class='"tag-type-" + form.type' id="form">
                        <div class="mb-4 p-5">
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>

                            <Field name="name" v-model="form.name" type="text" @change="autoTranslate"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Titulo" />
                            <ErrorMessage name="name" class=" text-xs italic uppercase"
                                :class="{ 'error-text': form.type != 3 }" />

                            <div v-if="form.errors.name" class=" text-xs italic uppercase"
                                :class="{ 'error-text': form.type != 3 }" v-html="form.errors.name"></div>
                        </div>


                        <div class="mb-4 p-5">
                            <label for="Traducion"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tradución
                            </label>
                            <Field name="translate" v-model="form.translate" type="text" id="Traducion"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Tradución en Español" />
                            <ErrorMessage name="translate" class=" text-xs italic uppercase"
                                :class="{ 'error-text': form.type != 3 }" />
                            <div v-if="form.errors.translate" class=" text-xs italic uppercase"
                                :class="{ 'error-text': form.type != 3 }" v-html="form.errors.translate"></div>


                        </div>

                        <div class="mb-4 p-5">
                            <label for="Traducion"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Otros
                                Nombres
                            </label>
                            <Field name="others_names" v-model="form.others_names" type="text" id="Traducion"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="otros nombres separados por coma ," />
                            <ErrorMessage name="others_names" class=" text-xs italic uppercase"
                                :class="{ 'error-text': form.type != 3 }" />

                        </div>



                        <div class="mb-4 p-5">
                            <label for="Descripción"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripcion</label>
                            <Field as="textarea" name="wiki" v-model="form.wiki" type="url" id="Descripción"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Descripción" />
                            <ErrorMessage name="wiki" class=" text-xs italic uppercase"
                                :class="{ 'error-text': form.type != 3 }" />
                            <p class="text-right text-white">{{ form.wiki.length }}/2500</p>
                        </div>

                        <div class="mb-4 p-5">

                            <label for="type"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seleciona
                                el tipo</label>
                            <Field v-model="form.type" name="type" as="select"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="-1">Chosse type</option>
                                <option value="0">General</option>
                                <option value="1">Copyright</option>
                                <option value="2">Character</option>
                                <option value="3">Artist</option>
                                <option value="4">Meta</option>
                                <option value="5">Modelo</option>
                            </Field>
                            <ErrorMessage name="type" class=" text-xs italic uppercase"
                                :class="{ 'error-text': form.type != 3 }" />
                        </div>


                        <div v-if="form.type == 3 || form.type == 5" class="mb-4 p-5">
                            <h2 class="h2" v-if="tag">
                                no funciona en edicion
                            </h2>
                            <label for="url_artist"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Url del
                                artista</label>
                            <Field as="textarea" v-model="form.ulr_artist" name="url_artist" placeholder="url por linea"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </Field>
                            <ErrorMessage name="url_artist" class=" text-xs italic uppercase"
                                :class="{ 'error-text': form.type != 3 }" />
                            <div v-if="form.errors.ulr_artist" class=" text-xs italic uppercase"
                                :class="{ 'error-text': form.type != 3 }" v-html="form.errors.ulr_artist"></div>
                        </div>
                        <div class="text-center">
                            
                            <button type="submi"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                :disabled="form.processing">Guardar</button>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>


<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { VueElement, onMounted } from 'vue'; // Importa el hook onMounted de Vue
import { createInertiaApp, useForm } from '@inertiajs/vue3'
import { Form, Field, ErrorMessage } from "vee-validate";
import * as yup from 'yup';
import { computed } from 'vue';


const { tag, url_list } = defineProps(['tag', 'url_list']);
const bgColour = ['tag-type-0',]



const form = useForm({
    name: tag ? tag.name : "", // Si hay datos iniciales, utiliza el nombre existente, de lo contrario, deja el campo vacío
    translate: tag ? tag.translate_esp : "", // Si hay datos iniciales, utiliza la traducción existente, de lo contrario, deja el campo vacío
    wiki: tag ? tag.wiki : "",
    type: tag ? tag.category : -1,
    ulr_artist: tag ? url_list : "",
    others_names: tag ? tag.others_names : "",
});



const autoTranslate = () => {
    if (form.translate == "") {
        form.translate = form.name
    }
}


const submitForm = () => {
    if (!tag) {
        form.post('/tags', {
            preserveScroll: true,
            onSuccess() {
                form.reset()
                form.clearErrors()
            }
        });
    } else {
        form.put(`/tags/${tag.id}`);
    }
};

const mySchema = yup.object({
    name: yup.string().required('El nombre es requerido'),
    translate: yup.string().required('tradución requerida'),
    wiki: yup
        .string().max(2500, 'maximo 2500').nullable(true),
    type: yup.number().required().min(0, "Seelciona una etiqueta").max(5)
})
</script>

<style>
.error-text {
    color: red;
}

/*bg-zinc-500*/

.tag-type--1 {
    background-color: rgb(113 113 122 / var(--tw-bg-opacity));
}

.tag-type-0 {
    background-color: #009be6;
}


.tag-type-1 {
    background-color: #c797ff;
}


.tag-type-2 {
    background-color: #93e49a;
}


.tag-type-3 {
    background-color: #ff8a8b;
}


.tag-type-4 {
    background-color: #ead084;
}

.tag-type-5 {
    background-color: #900e94;
}
</style>
