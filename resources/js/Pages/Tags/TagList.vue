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
                    <table class="table border w-full bg-white">
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
                            <tr v-for="tag in tags.data" :key="tag.id">
                                <td class="border p-3   ">{{ tag.id }} </td>
                                <td class="border p-3">
                                    <Link :href="route('tags.show', tag)">{{ tag.name }}</Link>
                                </td>
                                <td class="border p-3 translate-table">{{ tag.translate_esp }}</td>
                                <td class="border p-3 wiki-table">{{ tag.wiki }}</td>
                                <td v-if="tag.category == 0" class="border p-3">General </td>
                                <td v-if="tag.category == 1" class="border p-3">Copyright </td>
                                <td v-if="tag.category == 2" class="border p-3">Character </td>
                                <td v-if="tag.category == 3" class="border p-3">Artist </td>
                                <td v-if="tag.category == 4" class="border p-3">Meta </td>
                                <td class="border p-3"> {{tag.image_posts_count }}</td>
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
import { router } from '@inertiajs/vue3';
defineProps({ tags: Array })
</script>

<style>
@media only screen and (max-width: 600px) {
    .wiki-table{
        display:none;
    }

    .translate-table{
        display: none;
    }
}


</style>