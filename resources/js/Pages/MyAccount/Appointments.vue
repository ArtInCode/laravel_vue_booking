<script setup>
import { onMounted, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import ModelPagination from '@/Components/ModelPagination.vue';
import Header from '@/Components/Public/Header.vue'; 
import AppointmentsRow from '@/Pages/MyAccount/AppointmentsRow.vue'; 
import FlashMessage from '@/Components/FlashMessage.vue'; 

const props = defineProps({    
    appointments : [  Object, Array ], 
    statuses : [  Object, Array ]
});
const form = useForm({
    messages : []
});
const editRoutLink = ref(route('appointments.edit', {id : 1}));

editRoutLink.value = editRoutLink.value.split('/').slice(0, editRoutLink.value.split('/').length - 1).join('/');


</script>

<template>
    <Head title="My Account | Appointments" />
    <Header />
    
     <FlashMessage  v-model="form.messages"
                type="success"></FlashMessage>
    <div class="m-auto w-1/2 py-12"> 
        <h1>My Appointments</h1>    
        <div :class="[ 'bg-gray-700' , 'grid grid-cols-8 gap-4 text-white align-middle place-items-center border border-white px-1 py-1 mt-5 text-gray-900']">
            <div class="align-middle " > ID</div>
            <div class="align-middle " > Product Name  </div>
            <div class=""> Status </div>
            <div class="align-middle">Total</div>
            <div class="align-middle">Date</div>
            <div class="align-middle">Hour</div>
            <div class="align-middle">Quantity</div>
            <div>             
            </div>
        </div>   
        <div v-show="appointments.data.length" v-for="(item, index) in  appointments.data" :key="index" :id="`item-${item.id}`" :class="[ index % 2 === 0 ? ' bg-emerald-700' : 'bg-gray-700', 'grid grid-cols-8 gap-4 text-white align-middle place-items-center border border-white px-1 py-1 mt-5 text-gray-900']">
            <AppointmentsRow :item="item" :statuses="statuses" v-model="form.messages"/>
        </div>
        <div v-show="!appointments.data.length" class="text-white mt-5">
            No Appointment is saved
        </div>
        <div v-if="appointments.data.length > 0" class="mt-5 text-white">
          <ModelPagination :links="appointments.links" :mode="`light`"></ModelPagination>
        </div>
        
    </div> 
</template>