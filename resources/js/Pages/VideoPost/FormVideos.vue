<template>
    <AppLayout title="New Video">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Nueva Video
            </h2>
        </template>

        <div class="py-12  col   ">
            <div class="grid grid-cols-2 gap-6   max-w-7xl mx-auto sm:px-6 lg:px-12 ">
                <div>
                    <Form :validation-schema="mySchema" @submit="submitForm"
                        class="  w-full max-w-xl   bg-zinc-500 border-green-400 border-2 rounded-2xl" id="form">
                        <div class="mb-4 p-5">
                            <label for="title"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>

                            <Field name="title" v-model="form.title" type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Titulo" />
                            <ErrorMessage name="title" class="text-green-500 text-xs italic uppercase" />
                        </div>


                        <div class="mb-4 p-5">
                            <label for="url" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">URL
                                de la imagen</label>
                            <Field name="original_url" v-model="form.original_url" type="text" id="url"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="URL"/>
                                <ErrorMessage name="original_url" class="text-green-500 text-xs italic uppercase" />

                        </div>

                        <div class="mb-4 p-5">
                            <input type="checkbox" v-model="form.pegi18"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                @input="changeColor">
                            <label for="disabled-checkbox" class="ml-2 text-sm font-medium text-white">+18</label>
                        </div>

                        <div class="mb-4 p-5">
                            <label for="video"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Imagen</label>
                            <Field name="video" type="file" id="video"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white "
                                @input="handleImageChange" @change="onFileChange"/>

                                <ErrorMessage name="video" class="text-green-500 text-xs italic uppercase" />
                        </div>

                        <div v-if="form.video">Peso {{ form.video.type }} {{ form.video.size }}</div>

                        <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700" v-if="form">
                            <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" v-if="form.progress"
                            :style="{ width: form.progress.percentage + '%' }"> {{ form.progress.percentage }} %</div>
                        </div>

                        <div class="text-center">
                            <button type="submi"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                :disabled="form.processing">Guardar</button>
                        </div>
                    </Form>
                </div>

                <div class=" bg-white">
                    <h1>videon</h1>
                    <a href="https://www.youtube.com/watch?v=tHnwV5ay8-8" data-fancybox="video-gallery"   data-type="html5video"
>
                        <img src="https://www.w3schools.com/tags/img_girl.jpg" alt="video" width="500" height="600"
                            id="videoPreview">
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
import { onMounted } from 'vue'; // Importa el hook onMounted de Vue
import { useForm } from '@inertiajs/vue3'
import { Form, Field, ErrorMessage } from "vee-validate";
import * as yup from 'yup';



const form = useForm({
    title: "",
    original_url: "",
    pegi18: false,
    video: null,
})



const submitForm = () => {
    //form.validate().then(() => {
    //if (!form.errors.any()) {
    // Lógica de envío del formulario
    form.post('/videos');
    //}
    // });
};

const  mySchema = yup.object({
    title: yup.string().required('El nombre es requerido capullo'),
    original_url: yup.string().url('pon una url'),
    video: yup
    .mixed().required('video obligatoria')
    .test('image', 'El archivo debe ser un video', function (value) {
        
      if (!['video/mp4'].includes(value.type)  ) {
       // alert(9)
       // const allowedFormats = ['image/png', 'image/jpeg'];
       // return allowedFormats.includes(file.type);
       return false;
      }
   

      //return allowedFormats.includes(file.type);
     return true;
    }),
})




const handleImageChange = (event) => {
    var file = event.target.files[0];
    form.video = file;
   // document.getElementById('videoPreview').src = URL.createObjectURL(form.video);
    document.getElementById('videoPreview').parentElement.href = URL.createObjectURL(form.video);

};




const changeColor = (event) => {

if (!form.pegi18) {
    document.getElementById('form').classList.add("bg-red-900")
    document.getElementById('form').classList.remove("bg-zinc-500")

} else {
    document.getElementById('form').classList.remove("bg-red-900")
    document.getElementById('form').classList.add("bg-zinc-500")
}

document.getElementById('form').classList.add("bg-red-900")

}



onMounted(() => {
    // Lógica que se ejecutará después de que el componente se haya montado en el DOM
    Fancybox.bind('[data-fancybox]', {
        // Opciones de configuración de FancyBox
        // Puedes personalizarlo según tus necesidades
    });
});

</script>