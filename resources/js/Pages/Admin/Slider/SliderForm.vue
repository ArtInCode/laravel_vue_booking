<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Create Slider
            </h2>
        </template>
        <div class="m-auto w-1/2 py-12">
            <form @submit.prevent="submit">
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
                        <InputLabel for="link" value="link" />

                        <TextInput
                            id="link"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.link"
                            required
                            autofocus
                            autocomplete="link"
                        />

                        <InputError class="mt-2" :message="``" />
                    </div>
                 
                    <div class="w-full">
                        <InputLabel for="group_key" value="group_key" />

                        <TextInput
                            id="group_key"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.group_key"
                            required                            
                            autocomplete="group_key"
                        />

                        <InputError class="mt-2" :message="``" />
                    </div>
                    <div class="w-full">
                        <InputLabel for="status" value="status" />

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
                    <div class="w-full mt-5">
                        <button type="submit" class="text-white border-red-900 roundeed-md bg-red-900 display-block p-3">
                         {{ create ? 'Create Slider' : 'Update Slider' }}
                        </button>
                    </div>
                    
            </form>
            <div v-show="Object.keys(formError).length" class="mt-5 text-white">
                <ul>
                    <li v-for="error in formError"> {{ error }}</li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { onMounted, ref } from 'vue';
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

const page = usePage();
const formFieldsData = ref({
    title: '',
    description: '',  
    link: '',
    image_id: '',
    group_key: '',
    status : '', 
    fileName : ''  
});
const formAction = ref(route('slider.store'));
if(typeof page.props.create !== 'undefined' && page.props.create === false){
    formFieldsData.value = {
        title: page.props.sliderItem.title,
        description: page.props.sliderItem.description,  
        link: page.props.sliderItem.link,
        image_id: page.props.sliderItem.image_id,
        group_key: page.props.sliderItem.group_key,
        status : page.props.sliderItem.status,
        fileName : (typeof page.props.sliderItem.media.name !== 'undedefined' ) ? page.props.sliderItem.media.name : ''    
    }
    formAction.value = route('slider.update', {id : page.props.slider_id});
}

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

const props = defineProps({
    components: {
      EditorContent,
    },

    data() {
      return {
        editor: null,
        modelValue: [String, Number, Boolean, Object, Array], 
        fieldName: String,
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        mediaLink : '', 
        sliderItem: [Object]
      }
    },

    mounted() {
      
    },

    beforeUnmount() {
      
    },
    modelValue: [String, Number, Boolean, Object, Array],
    statuses: [ Object, Array], 
    options: [ Object, Array],
    csrf : String, 
    fieldName: String,
    mediaLink : String, 
    create : Boolean, 
    sliderItem : Object
});
const sliderItem = ref(null);
const emit = defineEmits(['update:modelValue']);

</script>