<template>
    <div class="align-middle " > # {{ item.id }} </div>
    <div class="align-middle"> {{ item.app_item.booking.title }}</div>
    <div class=""> {{ props.statuses[item.status] }}</div> 
    <div class="align-middle">{{ item.app_item  ? item.app_item.total : 0 }}</div>
    <div class="align-middle">{{ item.app_item  ? item.app_item.start_date : ' Not set' }}</div>
    <div class="align-middle">{{ item.app_item  ? item.app_item.start_hour : ' Not set' }}</div>
    <div class="align-middle">{{ item.app_item  ? item.app_item.quantity : ' Not set' }}</div>
    <div>   
        <a href="" v-show="item.status != 'cancelled' " @click.prevent="cancelAppointment(item.id)">Cancel</a>
    </div>
</template>
<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';

const props = defineProps({ 
    item : [  Object, Array ],
    statuses : [  Object, Array ], 
    modelValue: [  Object, Array ], 
});
const emit = defineEmits(['update:modelValue'])


async function cancelAppointment(){
    props.item.status = 'cancelled';
    try {
        const slotsResponse = await axios.post(route('appointments.myAppointment.cancel', {id : props.item.id}), {appId : props.item.id} , {
            headers: {
            'Content-Type': 'multipart/form-data'
            }
        });
        emit('update:modelValue', [{type : 'success',  text: 'Status is updated'}]);
        
    } catch (error) {
       
        emit('update:modelValue', [{type : 'error',  text: 'Something went wrong '}]);
    }
    
}

</script>