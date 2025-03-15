<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import { computed, reactive } from 'vue';
import { router, usePage } from '@inertiajs/vue3';


const carts = computed(() => usePage().props.cart.data.items);
const products = computed(() => usePage().props.cart.data.products);
const total = 2;
const auth = computed(() => usePage().props.auth);
const address = computed(() => usePage().props.useraddress);
const form = reactive({
    name: null,
    email: null,
    phone: null,
    message: null,
})
const formfilled = computed(() => {
    return (form.address1 !== null &&
        form.state !== null &&
        form.city !== null &&
        form.zipcode !== null &&
        form.country_code !== null &&
        form.type !== null)
})
const itemid = (id) => carts.value.findIndex((item) => item.product_id == id);

const update = (product, quantity) => {
    router.patch(route('cart.update', product), {
        quantity,
    });
}

const remove = (product) => {
    router.delete(route('cart.delete', product));
}

function submit() {
    router.visit(route('checkout.store'), {
        method: 'post',
        data: {
            carts: usePage().props.cart.data.items,
            products: usePage().props.cart.data.products,
            total: total,
            address: form,
        }
    })
}
function paypal() {
    router.visit(route('payment'), {
        method: 'post',
        data: {
            carts: usePage().props.cart.data.items,
            products: usePage().props.cart.data.products,
            total: '2',
            address: form,
            admin: 'admin'
        }
    })
}
</script>
<template >
    <div class="bg-gray-100 max-w-7xl max-h-52 m-28  ">
        <AdminLayout>
            <!--main content-->
            <section class="text-gray-600 body-font relative   ">
                <div
                    class=" mb-36 container px-5 py-24 mx-auto  sm:flex-nowrap flex-wrap  bg-white flex flex-col md:ml-auto w-1/2  h-1/3 md:py-8 md:mt-0 rounded-lg ">
                    <div class="m-5">
                        <h1 class="text-gray-900 text-2xl mb-1 font-medium title-font underline underline-offset-8 ">Contact
                            with Identity :-</h1>
                        <form>
                            <div class="relative mb-4">
                                <label for="name" class="leading-7 text-sm text-gray-600">Name</label>
                                <input type="text" id="name" name="name" v-model="form.name"
                                    class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                            <div class="relative mb-4">
                                <label for="email" class="leading-7 text-sm text-gray-600">Email</label>
                                <input type="email" id="email" name="email" v-model="form.email"
                                    class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                            <div class="relative mb-4">
                                <label for="phone" class="leading-7 text-sm text-gray-600">Phone</label>
                                <input id="phone" name="phone" v-model="form.phone"
                                    class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200  text-base outline-none text-gray-700 py-1 px-3  leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                            <div class="relative mb-4">
                                <label for="message" class="leading-7 text-sm text-gray-600">Message</label>
                                <textarea id="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write your thoughts here..."></textarea>
                            </div>
                            <div>
                                <button @click="submit()"
                                    class="text-white bg-blue-600 p-3  focus:outline-none hover:bg-indigo-300 rounded-full ">
                                    submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </AdminLayout>
    </div>
</template>