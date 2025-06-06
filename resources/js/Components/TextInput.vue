<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
const model = defineModel({
    type: [String, Number, null],
    required: true,
    default: null,    
});
const props = defineProps({
    mode : 'String',   
});
const input = ref(null);
onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
console.log('mode', props.mode);
</script>

<template>    
    <input
        :class="[typeof mode !== 'undefined' ? '' : 'dark:bg-gray-900 dark:text-gray-300',  'rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700  dark:focus:border-indigo-600 dark:focus:ring-indigo-600']"
        v-model="model"
        ref="input"
    />   
</template>
