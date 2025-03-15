<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import { computed, reactive } from 'vue';
import { router, usePage } from '@inertiajs/vue3';


const carts = computed(() => usePage().props.cart.data.items);
const products = computed(() => usePage().props.cart.data.products);
const total = computed(() => usePage().props.cart.data.total);
const auth = computed(() => usePage().props.auth);
const address = computed(() => usePage().props.useraddress);
const form = reactive({
    address1: null,
    state: null,
    city: null,
    zipcode: null,
    country_code: null,
    type: null,
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
            total: usePage().props.cart.data.total,
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
            total: usePage().props.cart.data.total,
            address: form,
        }
    })
}
</script>
<template >
    <div class="bg-gray-100 w-full h-full ">
        <AdminLayout>
            <!--main content-->
            <section class="text-gray-600 body-font relative  ">
                <div class="container px-5 py-24 mx-auto flex sm:flex-nowrap flex-wrap">
                    <div class=" w-2/3 bg-gray-100 rounded-lg  sm:mr-10 p-10 ">
                        <!--list of carts-->
                        <table class="w-2/3  text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 relative ">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-16 py-3">
                                        <span class="sr-only">Image</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Product
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Qty
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Price
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in products" :key="product.id"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="p-4">
                                        <img v-if="product.product_images.length > 0"
                                            :src="`/${product.product_images[0].image}`"
                                            class="w-16 md:w-32 max-w-full max-h-full" alt="Apple Watch">
                                        <img v-else
                                            src="https://th.bing.com/th?id=OIP.aWkAXBecah0BG4eGJr0gHQAAAA&w=209&h=297&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2"
                                            class="w-16 md:w-32 max-w-full max-h-full" alt="Apple Watch">
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                        {{ product.title }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <button @click="update(product, (carts[itemid(product.id)].quantity) - 1)"
                                                :disabled="carts[itemid(product.id)].quantity <= 0"
                                                class="inline-flex items-center justify-center p-1 me-3 text-sm font-medium h-6 w-6 text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                                type="button">
                                                <span class="sr-only">Quantity button</span>
                                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 18 2">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                                </svg>
                                            </button>
                                            <div>
                                                <input type="number" :id="`id-${product.id}`"
                                                    v-model="carts[itemid(product.id)].quantity"
                                                    class="bg-gray-50 w-14 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    :placeholder="[[product.quantity]]" required>
                                            </div>
                                            <button @click="update(product, (carts[itemid(product.id)].quantity) + 1)"
                                                class="inline-flex items-center justify-center h-6 w-6 p-1 ms-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                                type="button">
                                                <span class="sr-only">Quantity button</span>
                                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 18 18">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                        {{ product.price }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a @click="remove(product)"
                                            class="font-medium text-red-600 dark:text-red-500 hover:underline">Remove</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!--end-->
                    </div>
                    <div class=" bg-white flex flex-col md:ml-auto w-1/2 md:py-8 mt-8 md:mt-0 rounded-lg">
                        <div class="m-5">
                            <h2 class="text-gray-900 text-lg mb-1 font-medium title-font ">Summary</h2>
                            <p class="leading-relaxed mb-5 text-gray-600">Total : ${{ total }}</p>
                            <h2 class="text-gray-900 text-lg mb-1 font-medium title-font ">Shipping Address :</h2>
                            <div v-if="address">
                                <p class="leading-relaxed mb-5 text-gray-600">{{ address.address1 }},{{
                                    address.city }},{{ address.state }}</p>
                                <p class="leading-relaxed mb-5 text-gray-600">or you can add a new address</p>
                            </div>
                            <div v-else>
                                <p class="leading-relaxed mb-5 text-gray-600">Add a shipping address to continue</p>
                            </div>
                            <form >
                                <div class="relative mb-4">
                                    <label for="name" class="leading-7 text-sm text-gray-600">Address 1</label>
                                    <input type="text" id="name" name="name" v-model="form.address1"
                                        class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                                <div class="relative mb-4">
                                    <label for="city" class="leading-7 text-sm text-gray-600">city</label>
                                    <input type="text" id="city" name="city" v-model="form.city"
                                        class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                                <div class="relative mb-4">
                                    <label for="state" class="leading-7 text-sm text-gray-600">state</label>
                                    <input id="state" name="state" v-model="form.state"
                                        class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200  text-base outline-none text-gray-700 py-1 px-3  leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                                <div class="relative mb-4">
                                    <label for="ZipCode" class="leading-7 text-sm text-gray-600">ZipCode</label>
                                    <input id="ZipCode" name="ZipCode" v-model="form.zipcode"
                                        class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200  text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">
                                </div>
                                <div class="relative mb-4">
                                    <label for="Country_Code" class="leading-7 text-sm text-gray-600">Country Code</label>
                                    <input id="Country_Code" name="Country_Code" v-model="form.country_code"
                                        class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200  text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">
                                </div>
                                <div class="relative mb-4">
                                    <label for="Address_Type" class="leading-7 text-sm text-gray-600">Address Type</label>
                                    <input id="Address_Type" name="Address_Type" v-model="form.type"
                                        class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200  text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">
                                </div>

                                
                                </form>
                                <button v-if="formfilled || address" @click="submit()"
                                    class="text-white bg-white  focus:outline-none hover:bg-indigo-300 rounded-full ">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-20 mt-2" viewBox="0 0 24 24" id="stripe"
                                        width="200" height="900">
                                        <path fill="#646FDE"
                                            d="M11.319 9.242h1.673v5.805h-1.673zM4.226 13.355c0-2.005-2.547-1.644-2.547-2.403l.001.002c0-.262.218-.364.567-.368a3.7 3.7 0 0 1 1.658.432V9.434a4.4 4.4 0 0 0-1.654-.307C.9 9.127 0 9.839 0 11.029c0 1.864 2.532 1.561 2.532 2.365 0 .31-.266.413-.638.413-.551 0-1.264-.231-1.823-.538v1.516a4.591 4.591 0 0 0 1.819.382c1.384-.001 2.336-.6 2.336-1.812zM11.314 8.732l1.673-.36V7l-1.673.36zM16.468 9.129a1.86 1.86 0 0 0-1.305.527l-.086-.417H13.61V17l1.665-.357.004-1.902c.24.178.596.425 1.178.425 1.193 0 2.28-.879 2.28-3.016.004-1.956-1.098-3.021-2.269-3.021zm-.397 4.641c-.391.001-.622-.143-.784-.318l-.011-2.501c.173-.193.413-.334.795-.334.607 0 1.027.69 1.027 1.569.005.906-.408 1.584-1.027 1.584zm5.521-4.641c-1.583 0-2.547 1.36-2.547 3.074 0 2.027 1.136 2.964 2.757 2.964.795 0 1.391-.182 1.845-.436v-1.266c-.454.231-.975.371-1.635.371-.649 0-1.219-.231-1.294-1.019h3.259c.007-.087.022-.44.022-.602H24c0-1.725-.825-3.086-2.408-3.086zm-.889 2.448c0-.758.462-1.076.878-1.076.409 0 .844.319.844 1.076h-1.722zm-13.251-.902V9.242H6.188l-.004-1.459-1.625.349-.007 5.396c0 .997.743 1.641 1.729 1.641.548 0 .949-.103 1.171-.224v-1.281c-.214.087-1.264.398-1.264-.595v-2.395h1.264zm3.465.114V9.243c-.225-.08-1.001-.227-1.391.496l-.102-.496h-1.44v5.805h1.662v-3.907c.394-.523 1.058-.42 1.271-.352z">
                                        </path>
                                    </svg></button>
                                <button v-if="formfilled || address" @click="paypal()"
                                    class="text-white bg-wite  focus:outline-none hover:bg-indigo-300 rounded-full  ml-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class=" ml-2 h-12 w-20 mb-2 " width="200"  height="900" id="paypal">
                                        <path fill="#32A6CE" fill-rule="evenodd"
                                            d="M49.6 27c-1.5 0-2.7.4-3.7.6l-.3 2.3c.5-.2 1.9-.6 3.1-.7 1.2 0 1.9.2 1.7 1.3-3.6 0-6 .7-6.5 3.1-.7 4 3.7 4.1 5.4 2.3l-.2 1.1h3.2l1.4-6.5c.5-2.7-1.9-3.6-4.1-3.5zm.2 6.7c-.2.8-.9 1.1-1.7 1.2-.7 0-1.3-.4-.9-1.2.4-.6 1.3-.6 2-.6h.8c-.1-.1-.2.3-.2.6z"
                                            clip-rule="evenodd"></path>
                                        <path fill="#1A80AD" fill-rule="evenodd"
                                            d="M45.6 29.9c.5-.2 1.9-.6 3.1-.7 1.2 0 1.9.2 1.7 1.3-3.6 0-6 .7-6.5 3.1-.7 4 3.7 4.1 5.4 2.3l-.2 1.1h3.2l1.4-6.5c.6-2.7-1.9-3.4-4.1-3.4m.2 6.6c-.2.8-.9 1.1-1.7 1.2-.7 0-1.3-.4-.9-1.2.4-.6 1.3-.6 2-.6h.8c-.1-.1-.2.3-.2.6z"
                                            clip-rule="evenodd"></path>
                                        <path fill="#32A6CE" fill-rule="evenodd" d="m56.4 24-2.6 13H57l2.7-13h-3.3z"
                                            clip-rule="evenodd"></path>
                                        <path fill="#1A80AD" fill-rule="evenodd" d="m58.7 24-4.9 13H57l2.7-13h-3.3z"
                                            clip-rule="evenodd"></path>
                                        <path fill="#32A6CE" fill-rule="evenodd"
                                            d="M41.5 24h-5.9L33 37h3.5l.9-4h2.5c2.4 0 4.4-1.4 4.9-3.9.5-2.9-1.6-5.1-3.3-5.1zm-.1 4.5c-.2.9-1.1 1.5-2 1.5h-1.6l.7-3h1.7c.9 0 1.4.6 1.2 1.5z"
                                        clip-rule="evenodd"></path>
                                    <path fill="#1A80AD" fill-rule="evenodd"
                                        d="M41.5 24h-4l-4.6 13h3.5l.9-4h2.5c2.4 0 4.4-1.4 4.9-3.9.6-2.9-1.5-5.1-3.2-5.1zm-.1 4.5c-.2.9-1.1 1.5-2 1.5h-1.6l.7-3h1.7c.9 0 1.4.6 1.2 1.5z"
                                        clip-rule="evenodd"></path>
                                    <path fill="#21789E" fill-rule="evenodd"
                                        d="M18.4 27c-1.5 0-2.7.4-3.6.6l-.3 2.3c.4-.2 1.9-.6 3.1-.7 1.2 0 1.9.2 1.7 1.3-3.5 0-5.9.7-6.4 3.1-.7 4 3.6 4.1 5.3 2.3L18 37h3.2l1.4-6.5c.5-2.7-2-3.6-4.2-3.5zm.3 6.7c-.2.8-.9 1.1-1.7 1.2-.7 0-1.3-.4-.8-1.2.4-.6 1.3-.6 1.9-.6h.8c-.1-.1-.2.3-.2.6z"
                                        clip-rule="evenodd"></path>
                                    <path fill="#1A5B80" fill-rule="evenodd"
                                        d="M14.6 29.9c.4-.2 1.9-.6 3.1-.7 1.2 0 1.9.2 1.7 1.3-3.5 0-5.9.7-6.4 3.1-.7 4 3.6 4.1 5.3 2.3L18 37h3.2l1.4-6.5c.6-2.7-1.9-3.4-4.1-3.4m.2 6.6c-.2.8-.9 1.1-1.7 1.2-.7 0-1.3-.4-.8-1.2.4-.6 1.3-.6 1.9-.6h.8c-.1-.1-.2.3-.2.6z"
                                        clip-rule="evenodd"></path>
                                    <path fill="#21789E" fill-rule="evenodd"
                                        d="M23.9 27h3.2l.5 5.6 3.2-5.6h3.3l-7.6 14h-3.6l2.3-4.2-1.3-9.8z"
                                        clip-rule="evenodd"></path>
                                    <path fill="#1A5B80" fill-rule="evenodd"
                                        d="m27.1 27.2.5 5.5 3.2-5.7h3.3l-7.6 14h-3.6l2.3-4.1" clip-rule="evenodd">
                                    </path>
                                    <path fill="#21789E" fill-rule="evenodd"
                                        d="M10.5 24h-6L1.9 37h3.5l.9-4h2.5c2.4 0 4.4-1.4 4.9-3.9.6-2.9-1.5-5.1-3.2-5.1zm-.1 4.5c-.2.9-1.1 1.5-2 1.5H6.8l.7-3h1.7c.9 0 1.4.6 1.2 1.5z"
                                        clip-rule="evenodd"></path>
                                    <path fill="#1A5B80" fill-rule="evenodd"
                                        d="M10.5 24H7.8L1.9 37h3.5l.9-4h2.5c2.4 0 4.4-1.4 4.9-3.9.6-2.9-1.5-5.1-3.2-5.1zm-.1 4.5c-.2.9-1.1 1.5-2 1.5H6.8l.7-3h1.7c.9 0 1.4.6 1.2 1.5z"
                                        clip-rule="evenodd"></path>
                                    <path fill="#06435E" fill-rule="evenodd"
                                        d="M17.8 30.5c-2.7.2-4.5 1-4.9 3-.7 4 3.6 4.1 5.3 2.3L18 37h3.2l.5-2.4-3.9-4.1zm.9 3.2c-.2.8-.9 1.1-1.7 1.2-.7 0-1.3-.4-.8-1.2.4-.6 1.3-.6 1.9-.6h.8c-.1-.1-.2.3-.2.6zM27.8 32.5l-.2.3 2.1 2 4.4-7.8h-3.3zM5.4 37l.9-4-4.1 4z"
                                        clip-rule="evenodd"></path>
                                    <path fill="#2273AA" fill-rule="evenodd"
                                        d="m36.4 37 .9-4.2v.1L33.2 37zM48.9 30.5c-2.7.2-4.5 1-4.9 3-.7 4 3.7 4.1 5.4 2.3l-.3 1.2h3.2l.5-2.4-3.9-4.1zm.9 3.2c-.2.8-.9 1.1-1.7 1.2-.7 0-1.3-.4-.9-1.2.4-.6 1.3-.6 2-.6h.8c-.1-.1-.2.3-.2.6zM56.1 31.1 53.8 37H57l.9-4z"
                                        clip-rule="evenodd"></path>
                                    <g fill="#32A6CE">
                                        <path
                                            d="M60.4 26.1v-1.4h-.5v-.2h1.3v.2h-.5v1.4h-.3zM61.4 26.1v-1.6h.3l.4 1.1c0 .1.1.2.1.2 0-.1 0-.1.1-.3l.4-1.1h.3V26h-.2v-1.4l-.5 1.4H62l-.5-1.4V26h-.1z">
                                        </path>
                                    </g>
                                </svg></button>
                                <span v-else>Add an address to allow shopping</span>
                        
                        <p class="text-xs text-gray-500 mt-3">Continue Shooping</p>
                    </div>
                </div>
            </div>
        </section>
    </AdminLayout>
</div></template>