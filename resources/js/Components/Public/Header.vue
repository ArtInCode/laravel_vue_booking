<template>
    <header class="sticky top-0 z-50 shadow-md bg-green-800">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <!-- Logo -->
      <div class="flex-shrink-0">       
        <Link :href="route('home')">
            <ApplicationLogo
              class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
              />
        </Link>
      </div>

      <!-- Navigation -->
      <nav class="hidden md:flex space-x-8 text-white ">
        <Link :href="route('home')" class="hover:text-green-500">Home</Link>
        <li class="relative group inline-block hover:text-green-500">
          <a href="#" @click.prevent="">Booking</a>
          <ul class="absolute left-0  w-48 bg-white border border-gray-200 rounded shadow-lg opacity-0 group-hover:opacity-100 group-hover:visible invisible transition-opacity duration-200 z-50">
              <Link :href="route('bookings')" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Book Now</Link>
          </ul>
        </li>
        <li  :class="[accountLinks.htmlClass , 'pl-0 list-none']">
            <a :herf="accountLinks.href" class="hover:text-green-500"> {{ accountLinks.label }}</a>
            <ul v-show="accountLinks.isLoggedIn" class="absolute left-0  w-48 bg-white border border-gray-200 rounded shadow-lg opacity-0 group-hover:opacity-100 group-hover:visible invisible transition-opacity duration-200 z-50">
                <Link :href="accountLinks.account" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Appointments</Link>
            </ul>
        </li>       
      </nav>

      <!-- Mobile Menu Button -->
      <div class="md:hidden">
        <button type="button" class="text-gray-700 hover:text-blue-600 focus:outline-none">
          <!-- Hamburger icon -->
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</header>

</template>
<script setup>
import { ref, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';


const page = usePage();
const userLink = ref();
const props = defineProps({
    currentUser : [Array, Object],   
});

const accountLinks = ref({
  login : route('login'), 
  account : route('appointments.myAppointments'),  
  htmlClass : '' 
})

const settinsg = ref({});
onMounted(() => {
    settinsg.value = page.props; 
    accountLinks.value.link= settinsg.value.auth.user === null ? accountLinks.value.login : accountLinks.value.account;   
    accountLinks.value.htmlClass = settinsg.value.auth.user !== null ? 'relative group inline-block' : '';
    accountLinks.value.isLoggedIn = settinsg.value.auth.user !== null; 
    accountLinks.value.label = settinsg.value.auth.user === null ? 'Login' : settinsg.value.auth.user.name;
  
    console.log('cretedede',  settinsg.value);
})

</script>