<template>    
    <div class="text-white" id="`app-item-row`">
        <div class="grid grid-cols-5 gap-7" v-show="!booking.app_item || form.editAppItem">
            <div>      
                <InputLabel for="weekday" value="Select Booking" />     
                <SelectDropdown 
                    :options="form.appList" 
                    :selectedItem="form.booking_id" 
                    v-model="form.booking_id"
                    required 
                    @change="setBooking"
                    />
                
            </div>
            <div v-show="settings.showCalendar">  
                <InputLabel for="start_date" value="Select Date" />         
                 <TextInput
                    id="start_date"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.start_date"
                    required  
                    />
                 <ArrowPathIcon class="text-red-900 animate-spin h-6 w-6 " v-show="form.isProcessing"></ArrowPathIcon>
            </div>
            <div v-show="settings.showSlotsDropDown" >  
                <a href="#" v-for="slot in settings.slots" :key="slot.id" @click.prevent="selectSlotForAppointment(slot)" :dataId="slot.id" :class="[settings.selectedSlotId === slot.id ? 'text-green-900' : '', 'border border-white px-2 py-2 hover:text-green-900 inline-block']">
                    {{ slot.start_hour  }}
                    Price: {{ (slot.sale_price.length > 0) ? slot.sale_price : slot.price  }}
                    Quantity: {{ slot.quantity }}
                </a>
                
                <div v-if="settings.errorDate.length"> {{ settings.errorDate }}</div>            
            </div>
            <div v-show="settings.showQuantity" >
                <InputLabel for="quantity" value="Quantity" />
                <TextInput
                    id="quantity"
                    type="number"
                    min="1"
                    class="mt-1 block w-full"
                    v-model="form.quantity"
                    required  
                    @change="handleQuantity"
                    />
             </div>
             <div class="grid grid-cols-3 gap-3 pt-6" v-show="form.canAddNewAppItem">
               <div  @click="createAppointmentItem">
                    <CheckCircleIcon class="w-5 h-5 text-green-500" />
                </div>
             </div>
        </div>
        <div class="grid grid-cols-7 gap-7 bg-green-900 text-white mt-5" v-show="booking.app_item">
           <div>Name</div>
           <div>Date</div>
           <div>Hour</div>
           <div>Quantity</div>
           <div>Unit Price</div>
           <div>Total</div>             
        </div>       
        <div class="grid grid-cols-7 gap-7" v-if="booking.app_item">
            <div>
                <Link :href="route('bookings.edit', {id : booking.app_item ? booking.app_item.booking_id : 0 })" target="_blank">
                    {{  (typeof form.appList[booking.app_item.booking_id] !== 'undefined') ?  form.appList[booking.app_item.booking_id]   : 'App Id' + booking.app_item.booking_id }}
                </Link>
            </div>
            <div> {{  booking.app_item.start_date }}</div>
            <div> {{  booking.app_item.start_hour }}</div>
            <div> {{  booking.app_item.quantity }}</div>
            <div> {{  booking.app_item.unit_price }}</div>
            <div> {{  booking.app_item.total }}</div>
            <div class="flex">                
                <div  @click.prevent="showNewAppItemFields">
                    <PencilSquareIcon class="w-5 h-5 text-yellow-500" />
                </div>
                <div   @click.prevent="deleteAppointmentItem(booking.app_item.id)">
                    <TrashIcon class="w-5 h-5 text-red-500" />
                </div>
            </div>         
        </div>       
       
    </div>
    <p v-show="Object.keys(errors).length" v-for="(error, index) in errors" :key="index" class="text-red-900">           
        {{ error[0] }}
    </p>
   
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
import { PencilSquareIcon, TrashIcon, CheckCircleIcon, ArrowPathIcon } from '@heroicons/vue/24/outline';

const page = usePage();
const props = defineProps({

    data() {
      return {
        selectedItem:{},        
      }
    },
    weekDays: [Array, Object], 
    selectedItem : [String, Number],
    appItem: [Array, Object], 
    statusesList:  [Array, Object], 
    itemId : [String, Number],
    create : Boolean,  
    app_item : [Array, Object], 
    bookings :  [Array, Object],
    booking :  [Array, Object],
    weekDays : [Array, Object],
    modelValue: [Array, Object],
    parentPage : [Array, Object],
}); 

const settings = ref({
    showSlotsDropDown : 0, 
    showCalendar : 0,
    selectedSlotId : 0, 
    slots : [],
    showQuantity : 0,
    appItemCols : ['App Name'], 
    appKey : 'user.id', 
    errorDate : 0, 
});
const errors = ref([]);
const slotsForBookingDate = ref('');
const emit = defineEmits(['update:modelValue']);
onMounted(() => {    
    settings.appList = [];
    
    var b,a = {};
    a['0'] = 'Select'; 
    for(b in props.bookings){
        a[props.bookings[b].id] = props.bookings[b].title;            
    }
    settings.appList = a;
    form.appList = a;        
    
    if(Object.keys(props.bookings).length){
        var w, wList;
        wList = [];
        for(w in props.bookings){
            wList.push(props.bookings[w]);
        }
        settings.value.weekDays = wList;
        setCalendar();
    }
})
const formFieldsData = ref({
    unit_price: '',
    quantity: '',      
    start_hour: '',
    start_date : '', 
    unit_price :'',
    booking_id : '0',
    appList : {}, 
    appointment_id: props.itemId,
    subtotal : 0, 
    canAddNewAppItem : 0, 
    editAppItem : 0, 
    showQuantity : 0,
    isProcessing : 0,
});
const formAction = ref(route('appointments.items.store'));
if(typeof page.props.create !== 'undefined' && page.props.create === false && props.booking.app_item ){
    formFieldsData.value = {
        unit_price: props.booking.app_item.unit_price,
        quantity: props.booking.app_item.quantity,
        start_date: props.booking.app_item.start_date,  
        start_hour: props.booking.app_item.start_hour, 
        price : props.booking.app_item.price,
        sale_price : props.booking.app_item.sale_price,       
        booking_id : props.booking.app_item.booking_id,
        status : props.booking.status ,
        appList : {}, 
        appointment_id: props.itemId,
        subtotal : props.booking.app_item.subtotal, 
        canAddNewAppItem : 0,
        editAppItem : 0, 
        showQuantity : 0, 
        isProcessing : 0,
    }
    formAction.value = route('appointments.items.update', {id : props.booking.id});
}

const formError = ref([]);
var  form = useForm(formFieldsData.value);


function showNewAppItemFields(){
    form.editAppItem = !form.editAppItem;    
    if(form.booking_id !== ''){        
        fetchSlotsForBooking(form.booking_id);
    }    
}

async function createAppointmentItem(){    
    try {       
        if(typeof form.booking_id === 'undefined' && typeof  props.parentPage.props.item !== 'undefined' && typeof  props.parentPage.props.item.id !== 'undefined' ){
            form.appointment_id =  props.parentPage.props.item.id;
        }
       
        const response = await axios.post(
            props.booking.app_item ? route('appointments.items.update', {id : props.booking.app_item.id}) : route('appointments.items.store'), form, {
            headers: {
            'Content-Type': 'multipart/form-data'
            }
        });
        props.booking.app_item = Object.assign({}, props.booking.app_item, response.data.data);
        errors.value = [];
        emit('update:modelValue', [{type:'success', text:'Appointment Item is updated'}]);
       
    } catch (error) {
        emit('update:modelValue', [{type:'success', text:'Appointment Item failed to be updated'}]);        
        errors.value = error.response.data.errors;
    }
}
async function deleteAppointmentItem(num){    
    if(confirm('Are you sure')){
        const response = await axios.post(route('appointments.items.delete', {id : num}), {id : num}, {
            headers: {
            'Content-Type': 'multipart/form-data'
            }
        });       
        
        if(typeof response.data.success !== 'undefined' && true === response.data.success){
            props.booking.app_item = null; 
            emit('update:modelValue', [{type:'info', text:'Appointment Item is deleted'}]);           
        }
    }
    
}

function setBooking(e){    
    var b = {};
    for(b in props.bookings){
        if(props.bookings[b].id === (e.target.value - 0)){
            settings.selectedBooking = props.bookings[b];
            break;
        }
    }

    if(typeof settings.selectedBooking.id != 'undefined'){
        fetchSlotsForBooking(settings.selectedBooking.id);
    }   
}

async function fetchSlotsForBooking(bookingId){
    form.booking_id = bookingId;
    form.isProcessing = 1;
    try {
        const response = await axios.post(route('appointments.booking.slots'), {id : bookingId}, {
            headers: {
            'Content-Type': 'multipart/form-data'
            }
        });
        form.isProcessing = 0;
        settings.value.weekDays = response.data.weekDays;
        setCalendar();        
    } catch (error) {
        
    }
}

function setCalendar(){
    settings.value.showCalendar = 1;
     flatpickr("#start_date", {
        enableTime: false,
        minDate: "today",       
        disable: [
            {
                from: "1970-01-01",
                to: new Date().fp_incr(2)
            }, 
            function(date) {
            // return true to disable
            return (settings.value.weekDays.indexOf(date.getDay().toString()) === -1);
            }
        ],
        onChange: function(selectedDates, dateStr, instance) {            
            getSlotsForDate(dateStr)
        },
    
    });
}

async function getSlotsForDate(selectedDate){
    form.canAddNewAppItem = 0;
    
    try {
        const slotsResponse = await axios.post(route('slostForDate'), {bookingId : form.booking_id, date:selectedDate}, {
            headers: {
            'Content-Type': 'multipart/form-data'
            }
        });
        settings.value.errorDate = '';
        setSlots(slotsResponse.data.slots);        
    } catch (error) {        
        
        settings.value.errorDate = error.response.data.message;
        setSlots([]);         
        settings.value.slots = [];
    }
}

function setSlots(slots){
    settings.value.showSlotsDropDown = 1; 
    settings.value.slots = slots;    
}

function selectSlotForAppointment(slot){
    settings.value.selectedSlotId = slot.id;
    settings.value.selectedSlot = slot;
    form.unit_price = (slot.sale_price.length ) ? slot.sale_price : slot.price;
    form.start_hour = slot.start_hour;
    settings.value.showQuantity = 1; 
    
    if((form.quantity - 0) >= 1){
        form.canAddNewAppItem = 1;       
    }
}

function handleQuantity(el){
    var qtyNum = (el.target.value - 0);
    if(qtyNum > settings.value.selectedSlot.quantity){
        el.target.value = settings.value.selectedSlot.quantity;
    } else if(qtyNum < 1){
        el.target.value = 1
    }
    form.quantity = el.target.value; 
    form.subtotal = (form.quantity - 0) * (form.unit_price - 0);    
    form.canAddNewAppItem = 1;
}
</script>
