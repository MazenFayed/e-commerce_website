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
            admin:'admin'
        }
    })
}
</script>
<template >
    <div class="bg-gray-100 max-w-7xl max-h-52 m-28  ">
        <AdminLayout>
            <!--main content-->
            <section class="text-gray-600 body-font relative  ">
                <div
                    class="container px-5 py-24 mx-auto  sm:flex-nowrap flex-wrap  bg-white flex flex-col md:ml-auto w-1/2 h-1/3 md:py-8 mt-8 md:mt-0 rounded-lg ">
                    <div class="m-5">
                        <h1 class="text-gray-900 text-2xl mb-1 font-medium title-font underline ">Ask To Be Admin :-</h1>
                        <p class="leading-relaxed mb-5 text-gray-600">Pay: ${{ total }}/Month</p>
                       <!-- <button  @click="submit()"
                            class="text-white bg-white  focus:outline-none hover:bg-indigo-300 rounded-full ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-20 mt-2" viewBox="0 0 24 24" id="stripe"
                                width="200" height="900">
                                <path fill="#646FDE"
                                    d="M11.319 9.242h1.673v5.805h-1.673zM4.226 13.355c0-2.005-2.547-1.644-2.547-2.403l.001.002c0-.262.218-.364.567-.368a3.7 3.7 0 0 1 1.658.432V9.434a4.4 4.4 0 0 0-1.654-.307C.9 9.127 0 9.839 0 11.029c0 1.864 2.532 1.561 2.532 2.365 0 .31-.266.413-.638.413-.551 0-1.264-.231-1.823-.538v1.516a4.591 4.591 0 0 0 1.819.382c1.384-.001 2.336-.6 2.336-1.812zM11.314 8.732l1.673-.36V7l-1.673.36zM16.468 9.129a1.86 1.86 0 0 0-1.305.527l-.086-.417H13.61V17l1.665-.357.004-1.902c.24.178.596.425 1.178.425 1.193 0 2.28-.879 2.28-3.016.004-1.956-1.098-3.021-2.269-3.021zm-.397 4.641c-.391.001-.622-.143-.784-.318l-.011-2.501c.173-.193.413-.334.795-.334.607 0 1.027.69 1.027 1.569.005.906-.408 1.584-1.027 1.584zm5.521-4.641c-1.583 0-2.547 1.36-2.547 3.074 0 2.027 1.136 2.964 2.757 2.964.795 0 1.391-.182 1.845-.436v-1.266c-.454.231-.975.371-1.635.371-.649 0-1.219-.231-1.294-1.019h3.259c.007-.087.022-.44.022-.602H24c0-1.725-.825-3.086-2.408-3.086zm-.889 2.448c0-.758.462-1.076.878-1.076.409 0 .844.319.844 1.076h-1.722zm-13.251-.902V9.242H6.188l-.004-1.459-1.625.349-.007 5.396c0 .997.743 1.641 1.729 1.641.548 0 .949-.103 1.171-.224v-1.281c-.214.087-1.264.398-1.264-.595v-2.395h1.264zm3.465.114V9.243c-.225-.08-1.001-.227-1.391.496l-.102-.496h-1.44v5.805h1.662v-3.907c.394-.523 1.058-.42 1.271-.352z">
                                </path>
                            </svg></button>-->
                        <button  @click="paypal()"
                            class="text-white bg-wite  focus:outline-none hover:bg-indigo-300 rounded-full  ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class=" ml-2 h-12 w-20 mb-2 " width="200" height="900"
                                id="paypal">
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
                                    d="M23.9 27h3.2l.5 5.6 3.2-5.6h3.3l-7.6 14h-3.6l2.3-4.2-1.3-9.8z" clip-rule="evenodd">
                                </path>
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
                    </div>
                </div>
            </section>
        </AdminLayout>
    </div>
</template>