<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
</script>


<style>
.tooltip {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 520px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;

  /* Position the tooltip */
  position: absolute;
  z-index: 1;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}
</style>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Dashboard 
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">


<div class="tooltip">Hover over me
  <span class="tooltiptext">Tooltip text</span>
</div>

<br>
<div class="tooltip">Hover over me
  <span class="tooltiptext">
    
    <h1>Aqui el top oveffffffffffffffffffffffffffff </h1>
    <div class="bg-white text-black">
        Lorem, ipsum dolor sit amet consectetur adipisicing elit. 
        Aliquam autem accusantium, accusamus veritatis neque illo, 
        culpa asperiores nam eum rerum deserunt eius 
        sapiente vitae odit impedit aspernatur dolore 
        consequuntur suscipit!
    </div>
  </span>
</div>
                   
                   
                    <Welcome />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
