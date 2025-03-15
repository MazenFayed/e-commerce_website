<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';

defineProps({
    products: Array,
})

const addtocart = (product) => {
    router.post(route('cart.store', product), {
        onSuccess: () => {
            Swal.fire({
                toast: true,
                icon: 'success',
                position: 'top-end',
                showConfirmButton: false,
                title: usePage().props.flash.success
            });
        },
    })
}
</script>

<template>
    <div class="ml-64  ">
        <div class="cursor-pointer mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
            <div v-for="product in products" :key="product.id" class="group relative">
                <div
                    class=" aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
                    <img v-if="product.product_images.length > 0" 
                        :src="`/${product.product_images[0].image}`"  :alt="product.name"
                        class="h-full w-full object-cover object-center lg:h-full lg:w-full" />
                    <img v-else src="data:image/jpeg;base64,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" :alt="product.name"
                        class="h-full w-full object-cover object-center lg:h-full lg:w-full" />
                    <!-- add to cart -->
                    <div
                        class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer">
                        <div class="bg-blue-700 p-2 rounded-full">
                            <span class="">
                                <svg  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6 text-white  cursor-[url(hand.cur),_pointer]">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            </span>
                        </div>
                        <div class="bg-blue-700 p-2 rounded-full ml-2">
                            <a href="detail">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>

                            </a>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex justify-centre  " @click="addtocart(product)">
                    <div>
                        <h3 class="text-sm text-gray-700">
                            <span aria-hidden="true" class="absolute inset-0" />
                            {{ product.name }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Quantity:{{ product.quantity }}</p>
                        <p class="mt-1 text-sm text-gray-500">Name:{{ product.title }}</p>
                        <p class="mt-1 text-sm text-gray-500">Category:{{ product.category }}</p>
                        <p class="mt-1 text-sm text-gray-500">Brand:{{ product.brand }}</p>
                        <p class="mt-1 text-sm text-gray-500">Description:{{ product.description }}</p>
                    </div>
                    <div class="">
                        <p class="text-sm font-medium text-gray-900">${{ product.price }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>