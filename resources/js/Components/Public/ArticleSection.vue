<template>
    <div v-for="item in articles" :key="item.id" class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-1 col-span-3">
            <img :src="mediaLink +'/'+ item.media.name" alt="{{ item.title }}" class="img"/>
        </div>             
        <div class="md:col-span-2 col-span-3">
            <h2>{{ item.title }}</h2> 
            <div v-html="item.description"></div>
        </div>            
    </div>
</template>
<script setup>
const props = defineProps({
    articles: [Array, Object], 
    mediaLink : String,
})
</script>