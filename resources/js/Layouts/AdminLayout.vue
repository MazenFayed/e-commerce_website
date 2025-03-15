<template>
    <div class="antialiased bg-gray-100 ">
        <!-- Navbar start -->
        <Navbar />
        <!-- end -->

        

        <slot />

        <fouter />
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { initFlowbite } from 'flowbite';
import Navbar from '@/Components/navbar.vue';
import fouter from '@/Components/footer.vue';
onMounted(() => {
    initFlowbite();
})
</script>