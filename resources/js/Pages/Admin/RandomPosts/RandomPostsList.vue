<script setup>
import { onMounted, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import ModelPagination from '@/Components/ModelPagination.vue';
import { PencilSquareIcon, TrashIcon, CheckCircleIcon, ArrowPathIcon } from '@heroicons/vue/24/outline';
const props = defineProps({    
    sliders : [  Object, Array ], 
    posts : [  Object, Array ], 
    mediaLink : String,
    mounted() {     
      
    },
});
const editRoutLink = ref(route('random-post.edit', {id : 1}));
editRoutLink.value = editRoutLink.value.split('/').slice(0, editRoutLink.value.split('/').length - 1).join('/');
const deleteRoutLink = ref(route('random-posts.delete', { id : 1}));
deleteRoutLink.value = deleteRoutLink.value.split('/').slice(0, deleteRoutLink.value.split('/').length - 1).join('/');

async function deleteRandomPost(postId){
  console.log('sliderId', postId);
  try {
    const response = await axios.post(deleteRoutLink.value+'/'+sliderId, {id : postId}, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    if(typeof response.data.success !== 'undefined' && true === response.data.success){
        var delEl = document.getElementById('post-'+postId)
        if(delEl !== null){
            delEl.remove();
        }      
    }
  } catch (error) {    
    
  }
}
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>    
    <div class="m-auto w-1/2 py-12">
        <div>
          <Link :href="route('random-post.create')" class="rounded-md text-white bg-green-900 px-3 py-3">Create Random Post Item</Link>
        </div>
        <div v-if="posts.data.length" v-for="(item, index) in  posts.data" :key="index" :id=" posts.length ? `post-${item.id}` : `post-0` " :class="[ index % 2 === 0 ? ' bg-emerald-700' : 'odd', 'grid grid-cols-6 gap-4 text-white align-middle place-items-center border border-white px-1 py-1 mt-5']">
            <div class="align-middle" v-if="typeof item.media.name !== 'undefined'"><img :src="mediaLink + '/thumb-' +item.media.name" /> </div>
            <div class=""> {{ item.title }}</div>
            <div class="align-middle">{{ item.link }}</div>            
            <div class="align-middle">{{ item.group_key }}</div>
            <div class="align-middle">{{ item.status ? 'Enabled' : 'Disabled' }}</div>
            <div class="flex">
              <Link :href="editRoutLink  +'/'+item.id">
                <PencilSquareIcon class="w-5 h-5 text-yellow-500" />
              </Link>
            /              
              <a href="" @click.prevent="deleteSlider(item.id)">
                <TrashIcon class="w-5 h-5 text-red-500" />
              </a>
            </div>
        </div>
        <div v-show="!posts.data.length" class="text-white mt-5">
            No Post is saved
        </div>
        <div class="mt-5 text-white">
          <ModelPagination :links="posts.links"></ModelPagination>
        </div>
    </div>
    </AuthenticatedLayout>
</template>