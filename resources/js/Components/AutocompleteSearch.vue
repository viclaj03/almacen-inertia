<template>
  <div>
    
   <input v-if="useInput"  placeholder="tags_string d" :on-input="showList" @blur="hideSuggestions"
    ref="inputField"  type="text" :value="modelValue"  @input="$emit('update:modelValue', $event.target.value)" 
    class="block lg:w-96 w-full   p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
    
    /> 

    <textarea v-else
    placeholder="tags_string d" :on-input="showList" @blur="hideSuggestions"
    ref="inputField"   :value="modelValue"  @input="$emit('update:modelValue', $event.target.value)" 

    class="block lg:w-96 w-full   p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
></textarea>
    
    <ul class="list-surgest absolute z-10 rounded-md pl-2 pr-2" :hidden="!show_list "> 
     <li :class="categoryClass(suggestion.category)"  v-for="suggestion in suggestions" :key="suggestion.id" @click="selectSuggestion(suggestion)">
    {{ suggestion.name }}
  </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: ['modelValue','useInput'],
  emits: ['update:modelValue'],
  data() {
    return {
      suggestions: [],
      show_list:false,
      //useInput: true
    };
  },
  watch: {
    modelValue(newValue) {
      this.getSuggestions(newValue);
    }
  },
  methods:
   {
    
    showList(){
      
      this.show_list = true;
    },
    async getSuggestions(value) {
      this.show_list = true
      try {
        const tag_search = value.split(" ").pop();

        const response = await axios.get(`/api/tags?name=${tag_search.replace('_',' ')}`);
        
        this.suggestions = response.data;
      } catch (error) {
        console.error('Error fetching suggestions:', error);
      }
    },
    selectSuggestion(suggestion) {
      const tags_array =  this.modelValue.split(" ")
      tags_array[tags_array.length -1] = suggestion.name.replace(/\s+/g, '_')
      const tags_string = tags_array.join(' ')
      this.$emit('update:modelValue', tags_string);
      this.$refs.inputField.focus()
      
      //this.suggestions = [];
      this.show_list = false
      //this.suggestions = [];
    },

    categoryClass(category) {
    if (category === 0) return 'category-0';
    else if (category === 1) return 'category-1';
    else if (category === 2) return 'category-2';
    else if (category === 3) return 'category-3';
    else if (category === 4) return 'category-4';
    else return '';
  },
  hideSuggestions() {
     
      setTimeout(()=> {
        this.show_list = false
      },250)  
        
        //this.suggestions = [];
      
      //
    }
  },
};
</script>

<style scoped>
.list-surgest{
  background-color: #2c2d3f;
}

.category-1{
  color:#c797ff
}

.category-2{
  color:#93e49a
}

.category-0{
  color: #4bb4ff;
}

.category-3{
  color: #ddc9fb;
}

.category-4{
  color: #f7e7c3;
}


</style>
