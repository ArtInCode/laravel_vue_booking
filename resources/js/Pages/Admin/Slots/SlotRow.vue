<template>    
    <div class="text-white" :id="`slot-item-${props.slotItem.id}`">
        <div class="grid grid-cols-7 gap-7">
            <div>
                <InputLabel for="weekday" value="Weekday" />
                <SelectDropdown 
                :options="weekDays" 
                :selectedItem="form.weekday" 
                v-model="form.weekday"
                required 
                />
            </div>
            <div>
                <InputLabel for="start_hour" value="Start Hour" />
                <TextInput
                    id="start_hour"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.start_hour"
                    required  
                    />
             </div>
            <div>
                <InputLabel for="quantity" value="Quantity" />
                <TextInput
                    id="quantity"
                    type="number"
                    class="mt-1 block w-full"
                    v-model="form.quantity"
                    required  
                    />
             </div>
             <div>
                <InputLabel for="price" value="Price" />
                <TextInput
                    id="price"
                    type="number"
                    class="mt-1 block w-full"
                    v-model="form.price"
                    required    
                    />
             </div>
             <div>
                <InputLabel for="sale_price" value="Sale price" />
                <TextInput
                    id="sale_price"
                    type="number"
                    class="mt-1 block w-full"
                    v-model="form.sale_price"
                    required                            
                    autocomplete="duration"
                    />
             </div>
             <div>
                <InputLabel for="status" value="Status" />
                <SelectDropdown 
                :options="statusesList" 
                :selectedItem="form.status" 
                v-model="form.status"
                required 
                />
                <TextInput
                    id="booking_id"
                    type="hidden"
                    class="mt-1 block w-full"
                    v-model="form.booking_id"
                    required                            
                    autocomplete="duration"
                    />
             </div>
             <div class="grid grid-cols-3 gap-3 pt-6">               
                    <div v-show="typeof props.slotItem.id !== 'undefined'" @click.prevent="updateSlotRow">
                        <PencilSquareIcon class="w-5 h-5 text-yellow-500" />
                    </div>
                    <div v-show="typeof props.slotItem.id !== 'undefined'"  @click.prevent="deleteSlotRow">
                        <TrashIcon class="w-5 h-5 text-red-500" />
                    </div>
                    <div v-show="typeof props.slotItem.id === 'undefined'" @click.prevent="updateSlotRow">
                        <CheckCircleIcon class="w-5 h-5 text-green-500" />
                    </div>
             </div>
        </div> 
              
        <p v-show="Object.keys(errors).length" v-for="(error, index) in errors" :key="index" class="text-red-900">           
            {{ error[0] }}
        </p>
    </div>
</template>
<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import SelectDropdown from '@/Components/SelectDropdown.vue';
import TextInput from '@/Components/TextInput.vue';
import axios from 'axios';
import flatpickr from "flatpickr";
import 'flatpickr/dist/flatpickr.min.css';
import { PencilSquareIcon, TrashIcon, CheckCircleIcon } from '@heroicons/vue/24/outline';


const emit = defineEmits(['update:modelValue']);
onMounted(() => {
     flatpickr("#start_hour", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });
})
const page = usePage();
const props = defineProps({

    data() {
      return {
        selectedItem:{},
        
      }
    },
    weekDays: [Array, Object], 
    selectedItem : [String, Number],
    slotItem: [Array, Object], 
    statusesList:  [Array, Object], 
    item_id : [String, Number],
    create : Boolean, 
    parentPage :[Array, Object],    
}); 
const formFieldsData = ref({
    weekday: '',
    quantity: '',      
    start_hour: '',
    price :'',
    sale_price:'',
    enableSale :'',   
    status : '', 
    booking_id :  props.item_id  
});
const formAction = ref(route('slot.store'));
if(typeof page.props.create !== 'undefined' && page.props.create === false && typeof props.slotItem.weekday === 'string'){
    formFieldsData.value = {
        weekday: props.slotItem.weekday,
        quantity: props.slotItem.quantity, 
        start_hour: props.slotItem.start_hour, 
        price : props.slotItem.price,
        sale_price : props.slotItem.sale_price,       
        booking_id : props.slotItem.booking_id,
        status : props.slotItem.status  
    }
    formAction.value = route('slot.update', {id : props.slotItem.id});
}

const formError = ref([]);
var  form = useForm(formFieldsData.value);
console.log('weekDays', props.weekDays, props.selectedItem, page.props.create, formFieldsData.value, formAction.value,  form);
const errors = ref([]);
async function updateSlotRow(num){
    console.log('foem', form,  props.parentPage );
    if(typeof form.booking_id === 'undefined' && typeof  props.parentPage.props.item !== 'undefined' && typeof  props.parentPage.props.item.id !== 'undefined' ){
        form.booking_id =  props.parentPage.props.item.id;
    }
    errors.value = [];
    try {
        const response = await axios.post(formAction.value, form, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
      console.log('updateSlotRow', typeof props.slotItem.id , response);
      var delEl = null;
      if(typeof response.data.success !== 'undefined' && true === response.data.success){
        
        if(typeof props.slotItem.id === 'undefined'){
            
             Object.assign(form, {
                weekday: '',
                quantity: '',      
                start_hour: '',
                price :'',
                sale_price:'',
                enableSale :'',   
                status : '', 
                booking_id :  props.item_id  
            });           
            emit('update:modelValue', response.data.item.slots)
        }
      }    
    } catch (error) {
        console.log('post error', error, error.response.data.errors);
        errors.value = error.response.data.errors;

    }
    
}

async function deleteSlotRow(num){   
    if(confirm('Are you sure')){
        const response = await axios.post(route('slot.delete', {id : props.slotItem.id}), {id : props.slotItem.id}, {
            headers: {
            'Content-Type': 'multipart/form-data'
            }
        });
        console.log('updateSlotRow', typeof props.slotItem.id , response);
        var delEl = null;
        if(typeof response.data.success !== 'undefined' && true === response.data.success){
        
            delEl = document.getElementById('slot-item-'+props.slotItem.id)
            if(delEl !== null){
                delEl.remove();
            }
        }
    }    
}
</script>
<style>
.flatpickr-calendar{
    max-width: 135px;
}
</style>