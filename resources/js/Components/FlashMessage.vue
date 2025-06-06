<template>
 
  <div v-if="Object.keys(modelValue).length" class="mt-5">
    <div v-for="message in modelValue " :class="['alert', 'alert-'+message.type]" >
      <p >{{ message.text }}</p> 
    </div>
       
  </div>
</template>

<script setup>
import { computed, defineProps } from 'vue'

const props = defineProps({
  messages: [  Object, Array ],
  type: {
    type: String,
    default: 'info'
  },
   modelValue:  [  Object, Array ],
})

const alertClass = computed(() => {
  return {
    'alert-success': props.type === 'success',
    'alert-danger': props.type === 'error',
    'alert-info': props.type === 'info'
  }
})
</script>

<style>
.alert {
  padding: 1em;
  border-radius: 5px;
  margin-bottom: 1em;
}
.alert-success { background-color: #d4edda; color: #155724; }
.alert-danger { background-color: #f8d7da; color: #721c24; }
.alert-info { background-color: #cce5ff; color: #004085; }
</style>