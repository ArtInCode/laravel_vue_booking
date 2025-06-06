<template>
    <Header />
    <div>
         <img :src="mediaLink+'/'+item.media.name" class="w-full h-full object-cover" />
    </div>
    <div class="w-full md:w-1/2 m-auto mt-5 mb-5 px-2 py-2 md:px-5 md:py-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">                      
            <div class="md:col-span-2 col-span-3">
                <h2>{{ item.title }}</h2> 
                <div v-html="item.description"></div>
            </div>  
            <div class="md:col-span-1 col-span-3">
                <BookingForm :item="item" :itemId="itemId" :disabledWeekDays="disabledWeekDays"/>
            </div>             
        </div>
    </div>
    <div class="w-full md:w-1/2 m-auto mt-5 mb-5 px-2 py-2 md:px-5 md:py-5">
        <div class="mt-5 mb-5"><h2>Other Bookings</h2></div>
        <GridCards :items="bookings" :mediaLink="mediaLink" />
    </div>
</template>
<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import Header from '@/Components/Public/Header.vue'; 
import GridCards from '@/Components/Public/GridCards.vue'; 
import BookingForm from '@/Components/Public/BookingForm.vue'; 


const props = defineProps({   
    item : [Array, Object],  
    bookings :  [Array, Object],  
    mediaLink : String,
    itemId : [Number, String],
    disabledWeekDays : [Object, Array]
});
</script>