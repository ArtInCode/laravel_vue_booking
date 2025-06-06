<template>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div v-for="item in items" :key="item.id" class="col-span-1">
            <a :href="route('booking.item', {slug:item.title.replace(/ /g, '-'), id:item.id})">
            <img :src="mediaLink +'/'+ item.media.name" alt="{{ item.title }}" class="img"/>
            <h2>{{ item.title }}</h2> 
            </a>
        </div>
       
    </div>
</template>

<script setup>
const props = defineProps({
    items: [Array, Object], 
    mediaLink : String,
})
</script>