<template>

<div class="relative h-64 h-max">
      <template v-for="(item, index) in sliders" :key="index">          
        <div
          v-show="current === index"
          class="relative inset-0 transition-all duration-500"
          x-transition:enter="opacity-0"
          x-transition:enter-start="opacity-0"
          x-transition:enter-end="opacity-100"
          x-transition:leave="opacity-100"
          x-transition:leave-start="opacity-100"
          x-transition:leave-end="opacity-0"
        >
          <img :src="mediaLink+'/'+item.media.name" class="w-full h-full object-cover" />
          <div class="absolute slider-container max-w-[40%] px-5 py-5 border border-gray-800 bg-gray-100/30 rounded-md ">
            
            <h2 v-show="item.title.length" v-html="item.title.length > 1 ? item.title : '' "> </h2>
            <div class="" v-show="item.title.length" v-html="item.description.length > 1 ? item.description : ''"></div>
            
            
          </div>
        </div>
      </template>
    </div>
      
</template>
<script setup>
import { ref, onMounted } from 'vue';
const props = defineProps({
    sliders : [Array, Object], 
    mediaLink : String, 
   
});
const sliderLength = ref(0);
onMounted(() => {
    console.log('props.sliders', props.sliders);
    sliderLength.value  = Object.keys(props.sliders).length;
})
const current = ref(0);

function carousel() {
      return {
          current: 0,      
          init() {
            setInterval(() => this.next(), 5000)
          },
          next() {
            current.value = (current.value + 1) % sliderLength.value;          
          },
          prev() {
            current.value = (current.value - 1 + sliderLength.value) % sliderLength.value;
          },
          goTo(index) {
            current.value = index
          }
      }
}
carousel().init();


</script>
<style>
.slider-container{
    left:10%;
    top:20% ;
}
</style>