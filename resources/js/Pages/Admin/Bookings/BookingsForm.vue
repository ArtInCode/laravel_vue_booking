<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Booking Slider
                 
            </h2>
        </template>
        <div class="m-auto w-1/2 py-12">
            <form @submit.prevent="submit" ref="bookingForm">
                  <input type="hidden" name="_token" :value="csrf">
                    <div class="w-full">
                        <InputLabel for="title" value="Title" />

                        <TextInput
                            id="title"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.title"
                            required
                            autofocus
                            autocomplete="title"
                        />

                        <InputError class="mt-2" :message="``" />
                    </div>
                    <div class="w-full">
                        <InputLabel for="duration" value="Duration (in minutes)" />

                        <TextInput
                            id="duration"
                            type="number"
                            class="mt-1 block w-full"
                            v-model="form.duration"
                            required                            
                            autocomplete="duration"
                        />

                        <InputError class="mt-2" :message="``" />
                    </div>
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
                        <InputLabel for="title" value="Description" />
                        <RichTextEditor  v-model="form.description"></RichTextEditor>
                        
                        <InputError class="mt-2" :message="``" />
                    </div>
                    <div class="w-full">
                        <MediaField :fieldName="`slider_image_id`" :formFieldsData="formFieldsData" :mediaLink="mediaLink" v-model="form.image_id"/>
                    </div>
                    <div class="w-full">
                        <button type="submit" id="submitBookingForm"></button>
                    </div>                    
            </form>
            <div v-if="!create">
                <div class="w-full mt-5" v-if="typeof item !== 'undefined' " v-for="slot in item.slots" :key="slot.id">                        
                    <SlotRow :weekDays="weekDays" :statusesList="statuses"  :item_id="item_id" :slotItem="slot" :parentPage="page" />
                    </div>
                <div class="w-full mt-5">                                   
                    <SlotRow :weekDays="weekDays" :statusesList="statuses" :item_id="item_id" :parentPage="page" :slotItem="{}" @update:modelValue="item.slots = $event"  />
                </div>
            </div>
            <div class="w-full mt-5 ">                       
                <label for="submitBookingForm" class="text-white border-red-900 roundeed-md bg-red-900 display-block p-3"> 
                     {{ create ? 'Create Booking' : 'Update Booking' }} 
                </label>
            </div>
            <div v-show="Object.keys(formError).length" class="mt-5 text-white">
                <ul>
                    <li v-for="error in formError"> {{ error }}</li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SelectDropdown from '@/Components/SelectDropdown.vue';
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import RichTextEditor from '@/Components/RichTextEditor.vue';
import MediaField from '@/Components/MediaField.vue'; 
import SlotRow from '@/Pages/Admin/Slots/SlotRow.vue'; 
const page = usePage();

const props = defineProps({
    components: {
      EditorContent,
    },

    data() {
      return {
        editor: null,        
        fieldName: String,
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        mediaLink : '', 
        item: [Object], 
        item_id: 0,
      }
    },

    mounted() {  
    },

    beforeUnmount() {      
    },
   
    statuses: [ Object, Array], 
    options: [ Object, Array],
    csrf : String, 
    fieldName: String,
    mediaLink : String, 
    create : Boolean, 
    item : Object, 
    weekDays : [Array, Object],
    item_id : [Number, String]
});
console.log('props', props)

onMounted(() => {
  page.props.errors = {}, 
  page.props.item_id = 0;
})

watch(() => page.url, () => {
  page.props.errors = {};
  page.props.item_id = 0;
})
const formFieldsData = ref({
    title: '',
    description: '',      
    image_id: '',
    duration :'',
    enableSale :'',   
    status : '', 
    fileName : '', 
    booking_id :  props.item_id  
});
const formAction = ref(route('bookings.store'));
if(typeof page.props.create !== 'undefined' && page.props.create === false){
    formFieldsData.value = {
        title: page.props.item.title,
        description: page.props.item.description, 
        image_id: page.props.item.image_id, 
        duration : page.props.item.duration,
        enableSale : page.props.item.enableSale,       
        status : page.props.item.status,
        fileName : (typeof page.props.item.media.name !== 'undedefined' ) ? page.props.item.media.name : '', 
        booking_id :  props.item_id 
    }
    formAction.value = route('bookings.update', {id : page.props.item_id});
}
const item_id = ref(page.props.item_id);
const formError = ref([]);
const form = useForm(formFieldsData.value);
const content = ref('');


const submit = () => {
     const res =  form.post( formAction.value, {
        onSuccess: () => {
        
        },
        onError: (errors) => {
            formError.value = errors;
       
        },
        onFinish: () => {
        
        },
        preserveScroll: true,
    });
    
};




</script>