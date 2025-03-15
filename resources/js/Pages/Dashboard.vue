<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineProps({
    orders:Array,

});
</script>

<template>
    <AdminLayout>

        <Head title="Dashboard" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h2 class=" mt-10 mb-3 underline  font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"> Orders :-</h2>
                <div class="overflow-x-auto">
                        <table v-for="order in orders" :key="order.id" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                                <tr >
                                    <th scope="col" class="px-4 py-3">
                                        Order Id# {{ order.id }}
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Brand
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Category
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Item Price
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in order.order_items" :key="item.id" class="border-b dark:border-gray-700">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ item.product.title }}</th>
                                        <td class="px-4 py-3">{{ item.product.brand }}</td>
                                        <td class="px-4 py-3">{{ item.product.category }}</td>
                                        <td class="px-4 py-3">{{ item.product.price }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </AdminLayout>
</template>
