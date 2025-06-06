<template>
    <form @submit.prevent="submit" ref="bookingForm">
        <div >
            <InputLabel for="start_hour" value="Start Hour" class="dark:text-gray-900"/>
            <TextInput
                    id="app_date"
                    type="text"
                    placeholder="Select date"
                    class="mt-1 block w-full dark:bg-gray-100 dark:text-gray-900"
                    v-model="form.start_date"
                    required  
                    :mode="`light`"
                    />
            <div class="mt-4">
                <a href="#" v-for="slot in settings.slots" :key="slot.id" @click.prevent="selectSlotForAppointment(slot)" :dataId="slot.id" :class="[settings.selectedSlotId === slot.id ? 'text-green-900' : '', 'border border-gray-900 px-2 py-2 hover:text-green-900 inline-block w-full text-center']">
                    <b>{{ slot.start_hour  }}</b>
                    <br />
                    Price:${{ (slot.sale_price.length > 0) ? slot.sale_price : slot.price  }}
                    Quantity: {{ slot.quantity }}
                </a>
                <p v-show="settings.errorDate"> {{ settings.errorDate }}</p>
            </div>
        </div>
        <div v-show="settings.showQuantityInput">
            <InputLabel for="start_hour" value="Quantity" class="dark:text-gray-900"/>
            <TextInput
                    id="quantity"
                    type="number"
                    class="mt-1 block w-full dark:bg-gray-100 dark:text-gray-900"
                    :mode="`light`"
                    v-model="form.quantity"
                    required 
                    @input="adjustQuantity" 
                    />
        </div>
        <div v-show="settings.showQuantityInput">
            <InputLabel for="order_notes" value="Notes" class="dark:text-gray-900"/>
            <TextareaInput
                    id="order_notes"
                    rows="5"
                    class="mt-1 block w-full dark:bg-gray-100 dark:text-gray-900"
                    v-model="form.notes"
                    required 
                    @input="adjustQuantity"
                    :mode="`light`" 
                    />
        </div>
        <div>
            <PrimaryButton
                    class="relative ml-0 m-0 py-3 w-full justify-center dark:bg-gray-900 dark:text-white mt-4 dark:focus:bg-gray-800 dark:focus:text-gray-800 dark:active:bg-gray-800 dark:hover:bg-gray-800 hover:bg-gray-800 dark:hover:border-gray-900 dark:hover:text-gray-900"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="saveBooking"
                >
                Book Now
                <ArrowPathIcon class="text-red-900 animate-spin h-6 w-6 absolute left-3" v-show="form.isProcessing"></ArrowPathIcon>
            </PrimaryButton>
            
        </div>
    </form>

    <transition name="fade">
    <div
      v-show="settings.showModel" 
      class="fixed inset-0 flex items-center w-full justify-center z-50 bg-black bg-opacity-50"
      
    >
      <div class="bg-white rounded-2xl shadow-xl p-6 w-1/6 max-w-7xl min-h-[400px]">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold text-gray-800">Create Account</h2>
          <button @click="toggleModal" class="text-gray-400 hover:text-gray-700 text-xl">&times;</button>
        </div>
        <div class="text-gray-700 h-[300px] ">
            <form @submit.prevent="submit" ref="bookingForm">
                <div class="relative" >
                    <InputLabel for="title" value="Email" class="dark:text-gray-900"/>
                    <TextInput
                        id="user_email"
                        type="email"
                        class="mt-1 block w-full dark:bg-gray-100 dark:text-gray-900"
                        :mode="``"
                        v-model="form.user_email"
                        @change="validateNewEmail"
                        />
                    <div>
                        <InputError class="mt-2 absolute bottom-[-20px]" :message="settings.error.email" />                         
                    </div>           
                </div>
                <div class="relative mt-4">
                    <InputLabel for="title" value="Password" class="dark:text-gray-900"/>
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full dark:bg-gray-100 dark:text-gray-900"
                        v-model="form.password"
                        :mode="``"
                        @change="validatePassword"
                        />
                    <div>
                        <InputError class="mt-2 absolute bottom-[-20px]" :message="settings.error.password" />                         
                    </div>           
                </div>
                <div class="relative mt-4" >
                    <InputLabel for="title" value="Confirm Password" class="dark:text-gray-900"/>
                     <TextInput
                        id="password_confirmed"
                        type="password"
                        class="mt-1 block w-full dark:bg-gray-100 dark:text-gray-900"
                        v-model="form.password_confirmed"
                        :mode="``"
                        @change="validatePasswordConfirmed"
                        />
                    <div>
                        <InputError class="mt-2 absolute bottom-[-20px]" :message="settings.error.password_confirmed" />
                    </div>           
                </div>
                <div class="mt-5 relative">
                    <a href="" @click.prevent="submitBooking" class=" bg-green-500 text-white px-3 py-3 text-center rounded-md  inline-block">Create account and Appointment</a>                     
                </div>
                <div >
                    <InputError class="mt-2 " :message="settings.error.userExists" />
                </div>
            </form>
        </div>
        
      </div>
    </div>
  </transition>
</template>
<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import flatpickr from "flatpickr";
import 'flatpickr/dist/flatpickr.min.css';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import CreateAccountModeal from '@/Components/Public/CreateAccountModal.vue';
import InputError from '@/Components/InputError.vue';
import { PencilSquareIcon, TrashIcon, CheckCircleIcon, ArrowPathIcon } from '@heroicons/vue/24/outline';


const props = defineProps({   
    item : [Array, Object],    
    mediaLink : String,
    itemId : [Number, String],
    disabledWeekDays : [Array, Object],     
});

const page = usePage(); 
const formFieldsData = ref({
    bookingId: props.itemId,
    start_date: '',      
    start_hour: '',
    quantity :'',
    processing : 1,
    userId : 0, 
    notes : '', 
    createAccount : 0, 
    password_confirmed : '',
    password : '', 
    user_email : '',
});
const form = useForm(formFieldsData.value);
const dateSlots = ref('');
const settings = ref({
    showQuantityInput : 0, 
    showModel : 0,
    error : {}, 
    errorDate : 0,
});
function toggleModal() {   
    settings.value.showModel = !settings.value.showModel;              
}

onMounted(() => {
    form.processing = 1; 
     flatpickr("#app_date", {
        enableTime: false,
        minDate: "today",        
        disable: [
            {
                from: "1970-01-01",
                to: new Date().fp_incr(2)
            }, 
            function(date) {
            // return true to disable
            return (props.disabledWeekDays.indexOf(date.getDay().toString()) === -1);
            }
        ],
        onChange: function(selectedDates, dateStr, instance) {            
            getSlotsForDate(dateStr)
        },
    
    });
}); 

async function getSlotsForDate(date){
    try {
        form.quantity = 0;
        settings.value.showQuantityInput = 0;
        form.processing = 1;
        form.isProcessing = 1;
        const slotsResponse = await axios.post(route('slostForDate'), {bookingId : props.itemId, date:date}, {
            headers: {
            'Content-Type': 'multipart/form-data'
            }
        });
        form.isProcessing = 0;        
        settings.value.slots  = slotsResponse.data.slots;
        settings.value.errorDate = 0; 
        
    } catch (error) {
        form.isProcessing = 0;        
        settings.value.slots = [];
        settings.value.errorDate = error.response.data.message;
    }
}

function selectSlotForAppointment(slot){    
    settings.value.showQuantityInput = 1;
    settings.value.selectedSlot = slot;
    form.start_hour = slot.start_hour;
}

function adjustQuantity(e){
    var qty = (e.target.value - 0);
    if(qty < 1){
        e.target.value = 1;
    } else if(qty > (settings.value.selectedSlot.quantity - 0)){
        e.target.value = settings.value.selectedSlot.quantity; 
    }
    form.processing = 0; 
}

async function saveBooking() {    
    if(page.props.auth.user){
        form.userId = page.props.auth.user.id; 
        submitBooking();        
    } else {
        toggleModal();        
    }    
}

async function submitBooking(){
    const res =  form.post( route('bookings.create.frontend'), {
        onSuccess: () => {
        console.log('Success!')
        },
        onError: (errors) => {
            settings.value.error.userExists = errors.error;        
        },
        onFinish: () => {
        
        },
        preserveScroll: true,
    });
    
}


function validateNewEmail(e){
    if(!validEmail(e.target.value)){
        e.target.value = '';
        settings.value.error.email = 'Please enter value email';
        form.user_email ='';        
    } else {
        e.target.classList.remove('border-red-900');
        form.user_email = e.target.value;
        settings.value.error.email = '';        
    }
}

function validEmail(email) {
      var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
}
function validatePassword(e){
    if(e.target.value.length < 7){
        form.password = '';
        settings.value.error.password = 'Password must have at least 7 symbols';         
    } else {
        settings.value.error.password = '';
        form.password = e.target.value;
    }
}
function validatePasswordConfirmed(e){
    if(e.target.value.length < 7 || form.password !== e.target.value){
        settings.value.error.password_confirmed = 'Password does not match';
        form.password_confirmed = '';
    } else {
        settings.value.error.password_confirmed = '';
        form.password_confirmed = e.target.value;
    }
}
</script>