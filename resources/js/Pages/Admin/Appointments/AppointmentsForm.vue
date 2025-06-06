<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Appointments
            </h2>
        </template>
        <div class="m-auto w-1/2 py-12">
            <form @submit.prevent="submit" ref="bookingForm">
                <input type="hidden" name="_token" :value="csrf">
                <button type="submit" id="submitBookingForm"></button>    
                    <div class="w-full">
                        <InputLabel for="status" value="Status" />

                        <SelectDropdown
                            id="status"                   
                            class="mt-1 block w-full"
                            v-model="form.status"
                            required                            
                            :options="statuses"
                            :selectedItem="form.status" 
                        />

                        <InputError class="mt-2" :message="``" />
                    </div>
                    <div class="w-full">
                        <div v-show="item.user" class="text-white flex mt-3 mb-3">
                            <div class="px-2 py-2">User: {{ (item.user ) ? item.user.name +' | '+ item.user.email : '' }}</div>
                            
                             <div @click.prevent="updateAppointmentUser" class="px-2 py-2">
                                <PencilSquareIcon class="w-5 h-5 text-yellow-500" />
                            </div>
                        </div>
                       
                         <div v-if="!item.user || settings.showUserEditOptions" class="text-white">
                             <InputLabel for="status" value="Select User" />

                            <SelectDropdown
                                id="status"                   
                                class="mt-1 block w-full"
                                v-model="form.newUserType"
                                required                            
                                :options="userTypes"
                                :selectedItem="form.newUserType" 
                                @change="selectUserType"
                            />

                            <InputError class="mt-2" :message="``" />
                            <div v-show="settings.userType === '1'">
                                <InputLabel for="title" value="User Email" />

                                <TextInput
                                    id="new_user_email"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.new_user_email" 
                                    @change="validateNewEmail"                               
                                />
                            </div>
                            <div v-show="settings.userType === '2'">
                                <InputLabel for="title" value="User Email or name" />

                                <TextInput
                                    id="existing_user_email"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.existing_user_email"
                                    @keyup="searchExistingUser"
                                />
                                <div>
                                    <p v-show="settings.showUsersDropdown" v-for="user in settings.users" @click="selectUserFromDropDown(user)"  class="text-white border-white py-2 px-2 mb-2 cursor-pointer hover:text-green-800"> {{ user.name }}</p>
                                </div>
                            </div>
                         </div>
                       
                    </div>                                       
            </form>
           <div>
            <h2 class="text-white">{{ !item.app_item ? 'Add Booking item' : 'Booking Info' }}</h2>
            <AppointmentItemRow :itemId="itemId" :booking="item" :bookings="bookings" :weekDays="weekDays" v-model="form.messages" :parentPage="page"/>
           </div>
                    
            <div class="w-full mt-5 ">                       
                <label for="submitBookingForm" class="text-white border-red-900 roundeed-md bg-red-900 display-block p-3"> 
                    {{ create ? 'Create Appointment' : 'Update Appointment' }} 
                </label>
            </div>
            <div v-show="Object.keys(formError).length" class="mt-5 text-white">
                <ul>
                    <li v-for="error in formError"> {{ error }}</li>
                </ul>
            </div>
             <FlashMessage 
                v-model="form.messages"
                type="success" ></FlashMessage>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SelectDropdown from '@/Components/SelectDropdown.vue';
import { useEditor, EditorContent } from '@tiptap/vue-3'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AppointmentItemRow from '@/Pages/Admin/AppointmentsItem/AppointmentsItemRow.vue'; 
import { PencilSquareIcon, TrashIcon, CheckCircleIcon } from '@heroicons/vue/24/outline';
import FlashMessage from '@/Components/FlashMessage.vue'; 
const page = usePage();
const formFieldsData = ref({   
    notes :'',   
    status : '', 
    total :0,
    sub_total :0,
    user_id : '', 
    new_user_email : '',
    existing_user_email : '',
    selectedUserType : '1',
    newUserType : 0,
    messages : {}
});
const formAction = ref(route('appointments.store.admin.app'));
if(typeof page.props.create !== 'undefined' && page.props.create === false){
    formFieldsData.value = {              
        status : page.props.item.status,
        notes :page.props.item.notes,   
        status : page.props.item.status, 
        total : page.props.item.total,
        sub_total : page.props.item.sub_total,
        user_id : page.props.item.user ? page.props.item.user.id : 0,
        selectedUserType : '1',
        newUserType: 0,
        new_user_email : '',
        messages : {}
    }

    formAction.value = route('appointments.update', {id : page.props.itemId});
}
const itemId = ref(page.props.itemId);
const formError = ref([]);
const form = useForm(formFieldsData.value);
console.log('formFieldsData.value',formAction.value,  formFieldsData.value, page.props, form);

const settings = ref({
    userType : 0,
    userSearchTimeout : 0, 
    searchUserRoute :  route('appointments.search.user'),
    users : [],
    showUsersDropdown : 0, 
    showUserEditOptions : 0
});

const submit = () => {
    formError.value = [];
    if(settings.value.userType === '1' && (form.new_user_email.length === 0 || !validEmail(form.new_user_email))){
        formError.value.push('Please enter new user email');
        return false;
    } else if(settings.value.userType === '2' && form.user_id == ''){
        formError.value.push('Please select a user');
        return false;
    }
    console.log('form-submi', form)
     const res =  form.post( formAction.value, {
        onSuccess: () => {
        
        form.messages = [{type:'success', text:'Appointment is updated'}];
        },
        onError: (errors) => {
            formError.value = errors;
        
        },
        onFinish: () => {
        
        },
        preserveScroll: true,
    });
   
};

const props = defineProps({
    components: {
      EditorContent,
    },

    data() {
      return {        
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),       
        item: [Object], 
        itemId: 0,
      }
    },

    mounted() { 
    },

    beforeUnmount() {
      
    },    
    statuses: [ Object, Array],    
    csrf : String,
    create : Boolean, 
    item : Object,     
    itemId : [Number, String], 
    userTypes:[ Object, Array],  
    bookings : [ Object, Array],
    weekDays : [ Object, Array],
});

function updateAppointmentUser(){
    settings.value.showUserEditOptions = !settings.value.showUserEditOptions;
    form.selectedUserType = 2;
    form.newUserType = 2;
    console.log('form.selectedUserType', form);
}
function selectUserType(e) {
    console.log('userTypeChange',settings.value.userType = e.target.value);
}

function validateNewEmail(e){
    if(!validEmail(e.target.value)){
        e.target.value = '';
        e.target.classList.add('border-red-900');
        form.new_user_email ='';
    } else {
        e.target.classList.remove('border-red-900');
        form.new_user_email = e.target.value;
        console.log('validateNewEmail', form);
    }
}

async function searchExistingUser(e){
    if(e.target.value.length < 3 || e.target.value.length > 50) {
        return false;
    }
    clearTimeout(settings.value.userSearchTimeout);
    
    settings.value.userSearchTimeout =  setTimeout(async () => {        
            
         const response = await axios.post(settings.value.searchUserRoute, {user_name : e.target.value}, {
            headers: {
            'Content-Type': 'multipart/form-data'
            }
        });
        settings.value.users = response.data.users;
        settings.value.showUsersDropdown = 1;        
    }, 700);
    
}


function selectUserFromDropDown(user){
    form.user_id = user.id;
    form.existing_user_email = user.name;    
    settings.value.showUsersDropdown = 0; 
}

function validEmail(email) {
      var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
}

</script>