<script setup>
import { onMounted, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import ModelPagination from '@/Components/ModelPagination.vue';
import { PencilSquareIcon, TrashIcon, CheckCircleIcon, ArrowPathIcon } from '@heroicons/vue/24/outline';
const props = defineProps({    
    appointments : [  Object, Array ], 
    mediaLink : String,
    mounted() {
      axios.defaults.headers.common['X-CSRF-TOKEN'] = document.head.querySelector('meta[name="csrf-token"]');
      console.log('tokem', axios.defaults.headers.common['X-CSRF-TOKEN']);
      
    },
    statuses : [  Object, Array ]
});
const editRoutLink = ref(route('appointments.edit', {id : 1}));

editRoutLink.value = editRoutLink.value.split('/').slice(0, editRoutLink.value.split('/').length - 1).join('/');
const deleteRoutLink = ref(route('appointments.delete', { id : 1}));
deleteRoutLink.value = deleteRoutLink.value.split('/').slice(0, deleteRoutLink.value.split('/').length - 1).join('/');

async function deleteSlider(sliderId){
   
  try {
    const response = await axios.post(deleteRoutLink.value+'/'+sliderId, {id : sliderId}, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    if(typeof response.data.success !== 'undefined' && true === response.data.success){

        var delEl = document.getElementById('slider-'+sliderId)
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
            <Link :href="route('appointments.create.empty')" class="rounded-md text-white bg-green-900 px-3 py-3">Create Appointment</Link>
          </div>
          <div v-show="appointments.data.length" v-for="(item, index) in  appointments.data" :key="index" :id="`slider-${item.id}`" :class="[ index % 2 === 0 ? ' bg-emerald-700' : 'odd', 'grid grid-cols-6 gap-4 text-white align-middle place-items-center border border-white px-1 py-1 mt-5']">
              <div class="align-middle" > Appointment ID {{ item.id }} </div>
              <div class=""> {{ props.statuses[item.status] }}</div>                     
              
              <div class="align-middle">{{ item.sub_total }}</div>
              <div class="align-middle">{{ item.total }}</div>
              <div class="align-middle">{{ (  item.user   && typeof item.user.email != 'undefined') ? item.user.email : ' Not set' }}</div>
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
          <div v-show="!appointments.data.length" class="text-white mt-5">
              No Appointment is saved
          </div>
          <div v-if="appointments.data.length > 0" class="mt-5 text-white">
            <ModelPagination :links="appointments.links" ></ModelPagination>
          </div>
      </div>   
    </AuthenticatedLayout>
</template>