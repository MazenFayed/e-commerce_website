<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'

import hero from './components/hero.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import productlist from './components/listofproducts.vue';

/*import { onMounted } from 'vue';
import { initFlowbite } from 'flowbite';
onMounted(() => {
    initFlowbite();
})*/

const auth = usePage().props.auth;


defineProps({
    products: Array,
    productimages: Array,
    categories: Array,
    brands: Array,
})
const gotofilter = () => {
    router.visit(route('products.search'), {
        method: 'get',
        preserveState: true,
        replace: true,
        data: {
            path:'User/productfilter'
        }
    })
}


</script>
<template class="bg-gray-100 bg-contain mt-0" >
    <div class=" ">
        <AdminLayout>
        <hero class="md: mt-20 mr-40 "></hero>
        <!--main content-->
        <div class=" ">
            <div class=" py-8 px-4 max-w-screen-lg text-center  bg-gray-100 rounded-lg  mb-0   ">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 underline mb-0 text-left ml-64">Customers also Purchased :</h2>
                    <Link @click="gotofilter"
                        class=" ml-96 text-center  text-gray-900 bg-gradient-to-r from-teal-200 to-lime-200 hover:bg-gradient-to-l hover:from-teal-200 hover:to-lime-200 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-teal-700 font-medium rounded-lg text-sm px-5 py-2.5  me-2 ">
                    View All Products</Link>
                <productlist class="mb-10 w-full " :products="products">

                </productlist>
            </div>

        </div>

        </AdminLayout>
    </div>
</template>