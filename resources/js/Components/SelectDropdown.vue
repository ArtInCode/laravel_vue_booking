<template> 
    <select v-model="selectedOption"  class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600">
      <option value="" disabled>Select</option>
      <option v-for="(option, index ) in options" :key="index" :value="index" :selected="index == selectedItem ? true : false " >
        {{ option }}  
      </option>
    </select>  
</template>

<script setup>
import { ref } from 'vue'
const props = defineProps({    
    options : [  Object, Array ],
    selectedItem : [String, Number]
});
// List of options
 

// Selected value
const selectedOption = ref(props.selectedItem)
</script>