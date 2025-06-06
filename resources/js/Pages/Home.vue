<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import Header from '@/Components/Public/Header.vue'; 
import HeroSlider from '@/Components/Public/HeroSlider.vue'; 
import GridCards from '@/Components/Public/GridCards.vue'; 
import ArticleSection from '@/Components/Public/ArticleSection.vue'; 
const props = defineProps({
    sliders : [Array, Object],
    bookings : [Array, Object],
    welcome : [Array, Object],
    mediaLink : String,
});
</script>
<template>

<Header />
<HeroSlider :sliders="sliders" :mediaLink="mediaLink"/>
<div class="w-full md:w-1/2 m-auto mt-5 mb-5 px-2 py-2 md:px-5 md:py-5">
    <ArticleSection :articles="welcome" :mediaLink="mediaLink"/>
</div>
<div class="w-full md:w-1/2 m-auto mt-5 mb-5 px-2 py-2 md:px-5 md:py-5">
<GridCards :items="bookings" :mediaLink="mediaLink"/>
</div>
</template>

