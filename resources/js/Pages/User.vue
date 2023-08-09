<template>
  <AppLayout title="User">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Users
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

          <input
                                type="text"
                                v-model="search"
                                placeholder="Search..."
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-60 p-2.5"
                            />

          <table class="table border w-full bg-white">
            <thead>
              <tr>
                <th class="boder p-3">Img</th>
                <th class="border p-3">ID</th>
                <th class="border p-3">Name</th>
                <th class="border p-3">Email</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users.data" :key="user.id">
                <td class="border p-3 bg-green-950 flex justify-center items-center ">
                    
                  <a :href="user.profile_photo_url" data-fancybox="gallery">

                  <img class="rounded-full w-20 h-20 object-cover"
                      :src="user.profile_photo_url">
                      </a>
                      </td>
                <td class="border p-3">{{ user.id }}</td>
                <td class="border p-3">{{ user.name }}</td>
                <td class="border p-3">{{ user.email }}</td>
              </tr>
            </tbody>
          </table>
          <div class="bg-white">
            <Pagination :links="users.links" :page="users.current_page" />
          </div>
        </div>
      </div>

    </div>

    
  </AppLayout>
</template>
 

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Fancybox } from '@fancyapps/ui';
import '@fancyapps/ui/dist/fancybox/fancybox.css';
import { watch } from "vue";



import { router } from '@inertiajs/vue3'
import { ref } from "vue";

import { onMounted } from 'vue'; // Importa el hook onMounted de Vue


const props = defineProps({
    users: {
        type: Object,
        default: () => ({}),
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

let search = ref(props.filters.search);
watch(search, (value) => {
  router.get(
        "/user",
        { search: value },
        {
            preserveState: false,
            replace: false,
        }
    );
});


onMounted(() => {
  // Lógica que se ejecutará después de que el componente se haya montado en el DOM
  Fancybox.bind('[data-fancybox]', {
    // Opciones de configuración de FancyBox
    // Puedes personalizarlo según tus necesidades
  });
});

</script>