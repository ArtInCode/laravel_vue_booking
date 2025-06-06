<template>   
   
    <div v-if="links.length > 0" class="flex gap-2 mt-4">
      <button
        v-for="(link, i) in links"
        :key="i"
        :disabled="!link.url"
        @click="goToPage(link.url)"
        v-html="link.label"
        class="px-2 py-1 border rounded hover:bg-gray-100 hover:text-gray-900"
        :class="{ 'font-bold': link.active, 'text-gray-400': !link.url, 'text-gray-900' : typeof mode !== 'undefined' }"
      />
    </div>
</template>
<script setup>
import { onMounted, ref } from 'vue';
const props = defineProps({
    links: [Array, Object], 
    mode : String,
})
</script>