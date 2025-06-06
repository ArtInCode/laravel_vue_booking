<template>
     <transition name="fade">
    <div
      v-show="settings.showModel" 
      class="fixed inset-0 flex items-center w-full justify-center z-50 bg-black bg-opacity-50"
      @click.self="toggleModal"
    >
      <div class="bg-white rounded-2xl shadow-xl p-6 w-1/6 max-w-7xl ">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold text-gray-800">Create Account</h2>
          <button @click="toggleModal" class="text-gray-400 hover:text-gray-700 text-xl">&times;</button>
        </div>
        <div class="text-gray-700 h-[50vh]">
            <form @submit.prevent="submit" ref="bookingForm">
                <div >
                    <InputLabel for="title" value="Email" class="dark:text-gray-900"/>
                     <TextInput
                                    id="user_email"
                                    type="email"
                                    class="mt-1 block w-full dark:bg-gray-100 dark:text-gray-900"
                                    v-model="form.user_email"
                                    @change="validateNewEmail"
                                />
                    <div>
                        <InputError class="mt-2" :message="settings.error.email" />
                         
                    </div>           
                </div>
                <div >
                    <InputLabel for="title" value="Password" class="dark:text-gray-900"/>
                     <TextInput
                                    id="user_email"
                                    type="password"
                                    class="mt-1 block w-full dark:bg-gray-100 dark:text-gray-900"
                                    v-model="form.password"
                                    @change="validatePassword"
                                />
                    <div>
                        <InputError class="mt-2" :message="settings.error.password" />
                         
                    </div>           
                </div>
                <div >
                    <InputLabel for="title" value="Confirm Password" class="dark:text-gray-900"/>
                     <TextInput
                                    id="user_email"
                                    type="password"
                                    class="mt-1 block w-full dark:bg-gray-100 dark:text-gray-900"
                                    v-model="form.password_confirmed"
                                    @change="validatePasswordConfirmed"
                                />
                    <div>
                        <InputError class="mt-2" :message="settings.error.password_confirmed" />
                         
                    </div>           
                </div>
                <div class="mb-5">
                    <a href="" @click.prevent="bookSlot" class=" bg-green-500 text-white px-3 py-3 text-center rounded-md mt-5 inline-block">Create account and book</a>
                </div>
            </form>
        </div>
        
      </div>
    </div>
  </transition>
</template>
<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
const settings = ref({
  showModel: true, 
  error : {}
}); 
const form = useForm({
    user_email : '',
    password : '',
    password_confirmed : ''
}); 
const props = defineProps({   
    formData : [Array, Object],  
     
});

function toggleModal() {   
    settings.value.showModel = !settings.value.showModel;              
}

function validateNewEmail(e){
    if(!validEmail(e.target.value)){
        e.target.value = '';
        settings.value.error.email = 'Please enter value email';
        form.user_email ='';
        console.log('invalid email')
    } else {
        e.target.classList.remove('border-red-900');
        form.user_email = e.target.value;
        settings.value.error.email = '';
        console.log('validateNewEmail', form);
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
        settings.error.password
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

async function bookSlot(){
    console.log('bookSlot', Object.assign({}, props.formData, form))
}
</script>