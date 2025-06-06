<template>
      <div class="w-full mt-5">
          <label @click="toggleModal" class="text-yellow-500 border border-yellow-500 px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
            Select Image
          </label>
      </div>
      <div v-html="mediaPreviewContent" class="w-full mt-5"></div>
        
     
   <transition name="fade">
    <div
      v-show="settings.showModel" 
      class="fixed inset-0 flex items-center w-full justify-center z-50 bg-black bg-opacity-50"
      @click.self="toggleModal"
    >
      <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-7xl ">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold text-gray-800">{{ title }}</h2>
          <button @click="toggleModal" class="text-gray-400 hover:text-gray-700 text-xl">&times;</button>
        </div>
        <div class="text-gray-700 h-[80vh]">
            <div class="w-full pb-5">
                <a href="" @click.prevent="defineMediaSouce('upload')" :class="['rounded-md px-3 py-3 mr-2 border border-blue-900 ', settings.uploadNew ? ' text-white  bg-blue-900' : 'text-blue']">Upload File</a>
                <a href="" @click.prevent="defineMediaSouce('reuse')" :class="['rounded-md px-3 py-3 mr-2 border border-blue-900 text-blue', settings.uploadNew ? ' text-blue ' : ' text-white  bg-blue-900 ']">Media Files</a>
            </div>
            <div v-show="settings.uploadNew" class="w-full text-center">    
                <label for="{{ fieldName }}" class="border border-green-900 inline-block rounded-full text-width text-white px-5 py-14 m-auto bg-green-900">
                  
                  Upload Image
                </label>
                <input type="file" multiple @change="onFileChange" id="{{ fieldName }}" class="hidden"/>
                
                <p v-if="settings.message">{{ settings.message }}</p>
            </div>
            <div v-show="!settings.uploadNew">
                <div class="grid grid-cols-7 gap-7  w-full  h-[80vh]">
                    <div class="col-span-6">
                        <div v-html="mediaFilesContainer" ref="mediaFilesContainerHandle" @click.exact.ctrl="preselectImages" @click.exact="preselectImage" class=" grid grid-cols-6 gap-6  overflow-auto  h-[80vh]" @scroll="loadMoreMedia"></div>
                    </div>
                        
                    <div class="file-info-column col-span-1">
                        <div v-if="settings.imageIsSelected" class="w-full relative h-full">
                            <p>Width: {{ settings.mediaItemWidth }}</p>
                            <p>Height:{{ settings.mediaItemHeight }}</p>
                            <p>Type:{{ settings.mediaItemType }}</p>
                            <p><a href="" @click.prevent="deleteMediaItem" class="rounded-md text-red-800 inline-block   w-full mt-5">Delete {{ settings.allSelectedMedia.length > 1 ? 'Items' : 'Item' }}</a></p>

                            
                        </div>                        
                    </div>
                </div>
            </div> 
        </div>
        <div class="mt-6 flex justify-end">
            <ArrowPathIcon class="text-red-900 animate-spin h-6 w-6 mt-5 mr-5" v-show="settings.isLoading"></ArrowPathIcon>
            <a href="" @click.prevent="addSelectedImageToForm" class=" bg-green-500 text-white px-3 py-3 text-center rounded-md mt-3 inline-block">
              Set Image
               
            </a>
            
           
        </div>
      </div>
    </div>
  </transition>
  
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import {  ArrowPathIcon } from '@heroicons/vue/24/outline';

const settings = ref({
  showModel: false, 
  selectedFile: null,
  message: '',
  mediaItemWidth: '',
  mediaItemHeight: '',
  imageIsSelected: false,
  previewIsReady: false,
  selectedMediaData: {}, 
  mediaFilesNextPage : '', 
  loadMediaImages : true, 
  mediaItemType : '', 
  canLoadMore : false, 
  mediaMetaData : {}, 
  allSelectedMedia : [],
  isLoading : 0,
})

const mediaFilesContainer = ref('');
const mediaPreviewContent = ref(null);
const mediaGalleryItem = ref(null);
const mediaFilesContainerHandle = ref(null);
const props = defineProps({
    data(){
        return {
            fieldName : '',             
            mediaLink : '',
            formFieldsData: {}, 
            settings : {
              showModel : false
            }
        }
    },
    fieldName : String,     
    settings: Object,
    title: String,
    mediaLink: String,   
    modelValue: [String, Number],
    formFieldsData: Object,     
    methods : {
        
    }, 
    mounted() {
      axios.defaults.headers.common['X-CSRF-TOKEN'] = document.head.querySelector('meta[name="csrf-token"]');
      console.log('tokem', axios.defaults.headers.common['X-CSRF-TOKEN']);
      
    },
});

const emit = defineEmits(['close', 'update:modelValue']);
const close = () => emit('close');
function toggleModal() {   
    settings.value.showModel = !settings.value.showModel;
    settings.value.uploadNew = true;
    settings.value.loadMediaImages = true;
    console.log('show',  settings.value.showModel);            
}

function displayMediaFiles(items, type){
    var domain = props.mediaLink;    
    for(var mediaItem in items.data.items.data){    
         
        settings.value.mediaMetaData[items.data.items.data[mediaItem].id] = items.data.items.data[mediaItem].meta;
        mediaFilesContainer.value += '<div class="text-center  inline-block" id="media-item-'+items.data.items.data[mediaItem].id+'"> '+
          '<div class="border border-grey-500 px-2 py-2">'+
            '<img src="'+ domain +'/thumb-'+items.data.items.data[mediaItem].name+'"  class="media-item inline-block border-4"   data-id="'+items.data.items.data[mediaItem].id+'" ref="mediaGalleryItem"/>'+ 
          '</div>'+
          '</div>';        
    }

    if(typeof items.data.items.links[items.data.items.links.length - 1]['url'] === 'string'){
      settings.value.mediaFilesNextPage = items.data.items.links[items.data.items.links.length - 1]['url'];
    } else {
      settings.value.canLoadMore = false;
      console.log('displayMediaFiles', settings.value.canLoadMore)
    }
}


function addSelectedImageToForm(){   
    const selImg = '<img src="'+settings.value.selectedMediaData.src+'" />';
    mediaPreviewContent.value = selImg;     
    emit('update:modelValue', settings.value.selectedMediaData.id);
    settings.value.previewIsReady = true;    
    settings.value.showModel = false;
    console.log('selectedImage', settings.value.selectedMediaData)
   
}
function toggleLoading(){
  settings.value.isLoading = !settings.value.isLoading;
  console.log('settings.value.isLoading', settings.value.isLoading);
}
async function loadMoreMedia(){
    console.log('loadMoreMedia', settings.value.canLoadMore)
    if(!settings.value.canLoadMore){
      return false;
    }
    settings.value.canLoadMore = false;
     toggleLoading();
    try {
        
        const loadMoreResponse = await axios.get(settings.value.mediaFilesNextPage,  {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
       toggleLoading();
      settings.value.canLoadMore = true;
      displayMediaFiles(loadMoreResponse);
  } catch (error) {
     toggleLoading();
    console.error(error)
  }
}


function preselectImages(el){
  
   el.target.classList.add('border-green-900');
  if(settings.value.allSelectedMedia.indexOf(el.target.getAttribute('data-id')) === -1) {
    settings.value.allSelectedMedia.push(el.target.getAttribute('data-id'));
  }

}
function preselectImage(el){
     
    settings.value.allSelectedMedia = [];
    settings.value.allSelectedMedia.push(el.target.getAttribute('data-id'));
    mediaFilesContainerHandle.value.querySelectorAll('.border-green-900').forEach(el => {
      el.classList.remove('border-green-900')
    });
    el.target.classList.add('border-green-900')
    var itemMeta = JSON.parse(settings.value.mediaMetaData[el.target.getAttribute('data-id')]); 
    settings.value.mediaItemWidth = itemMeta.width;
    settings.value.mediaItemHeight = itemMeta.height;
    settings.value.mediaItemType = itemMeta.extension;
    settings.value.imageIsSelected = true;
    settings.value.selectedMediaData = {
        src: el.target.src, 
        id: el.target.getAttribute('data-id')
    }
    
    return el;
}
const defineMediaSouce = (type) => {    
    if(type === 'reuse'){
        settings.value.uploadNew = false;
        
        loadMediaFiles();
    } else {
        settings.value.uploadNew = true;
    }
}

const loadMediaFiles = async () => {
    if(!settings.value.loadMediaImages){
      return false;
    }
     toggleLoading()
    try {
        const response = await axios.get(route('media.files'),  {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    settings.value.loadMediaImages = false;
    settings.value.canLoadMore = true;
    settings.value.allSelectedMedia = [];
    mediaFilesContainer.value = '';
    displayMediaFiles(response);   
     toggleLoading() 
  } catch (error) {
    settings.value.message = 'Upload failed.';
     toggleLoading()
  }
}
function onFileChange (e){
  settings.value.selectedFile = Array.from(e.target.files); 
  e.target.value = null;  ;
  uploadImage();
  console.log('filechang', settings.value.selectedFile);
}

async function deleteMediaItem(){
    if(confirm('Are you sure')){ 
       toggleLoading();     
      const response = await axios.post(route('media.deleteItems'), {delItems : settings.value.allSelectedMedia}, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
       toggleLoading()
      console.log('del-res', response);
      var delEl = null;
      if(typeof response.data.success !== 'undefined' && true === response.data.success){
        settings.value.allSelectedMedia.forEach(el => {
          delEl = document.getElementById('media-item-'+el)
          if(delEl !== null){
            delEl.remove();
          } 
        })
      }
    }
}
const uploadImage = async () => {
  if (!settings.value.selectedFile) return
  settings.value.loadMediaImages = true;
  const formData = new FormData()
  
   settings.value.selectedFile.forEach((file, index) => {
    formData.append(`image[${index}]`, file)
  });
   toggleLoading();
  try {
    const response = await axios.post(route('media.apload'), formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    settings.value.uploadNew = false;
    loadMediaFiles();
     toggleLoading();  
  } catch (error) {
    settings.value.message = 'Upload failed.'
     toggleLoading();
  }
}

if(typeof props.formFieldsData.fileName === 'string' && props.formFieldsData.fileName.length){
   settings.value.selectedMediaData = {
        src: props.mediaLink+'/thumb-'+props.formFieldsData.fileName, 
        id: props.formFieldsData.image_id
    };
    addSelectedImageToForm();
}
</script>