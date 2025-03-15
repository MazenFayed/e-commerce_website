<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import productlist from './components/listofproducts.vue';
import { ref } from 'vue'
import {
    Dialog,
    DialogPanel,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { ChevronDownIcon, FunnelIcon, MinusIcon, PlusIcon, Squares2X2Icon } from '@heroicons/vue/20/solid'
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
const sortOptions = [
    { name: 'Most Popular', href: '#', current: true },
    { name: 'Best Rating', href: '#', current: false },
    { name: 'Newest', href: '#', current: false },
    { name: 'Price: Low to High', href: '#', current: false },
    { name: 'Price: High to Low', href: '#', current: false },
]

defineProps({
    products: Array,
    productimages: Array,
    categories: Array,
    brands: Array,
})

const mobileFiltersOpen = ref(false)
const filterPrices = useForm({
    prices: [0, 100000]
})

const productFilter = () => {
    filterPrices.transform((data) => ({
        ...data,
        prices: {
            from: filterPrices.prices[0],
            to: filterPrices.prices[1]
        },
        brands: selectedbrands.value,
        categories: selectedcategories.value,
        path: 'User/productfilter',
        orderedBydate:orderedBydate.value,
        orderedByprice:orderedByprice.value,
    })).get(route('products.search'), {
        preserveState: true,
        replace: true,
    })

}
const pagereload = () => {
    router.get('http://vshop/admin/products?path=User/productfilter');
}

// brand filter watch
const selectedbrands = ref([]);
// category filter watch
const selectedcategories = ref([]);
const orderedByprice =ref([]);
const orderedBydate = ref([]);

watch(orderedByprice, () => {
    //update the filtered products
    productFilter();
})
watch(orderedBydate, () => {
    //update the filtered products
    productFilter();
})
watch(selectedbrands, () => {
    //update the filtered products
    productFilter();
})
watch(selectedcategories, () => {
    //brands=[];
    productFilter();
})
 
const ascprices =()=>{
    orderedByprice.value='asc';
    productFilter();
}

const descprices =()=>{
    orderedByprice.value='desc';
    productFilter();
}
const ascdate =()=>{
    orderedBydate.value='asc';
    productFilter();
}

const descdate =()=>{
    orderedBydate.value='desc';
    productFilter();
}
</script>

<template>
    <div>
        <AdminLayout>
            <div class="bg-white">
                <div>
                    <!-- Mobile filter dialog -->
                    <TransitionRoot as="template" :show="mobileFiltersOpen">
                        <Dialog as="div" class="relative z-40 lg:hidden" @close="mobileFiltersOpen = false">
                            <TransitionChild as="template" enter="transition-opacity ease-linear duration-300"
                                enter-from="opacity-0" enter-to="opacity-100"
                                leave="transition-opacity ease-linear duration-300" leave-from="opacity-100"
                                leave-to="opacity-0">
                                <div class="fixed inset-0 bg-black bg-opacity-25" />
                            </TransitionChild>

                            <div class="fixed inset-0 z-40 flex">
                                <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                                    enter-from="translate-x-full" enter-to="translate-x-0"
                                    leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                                    leave-to="translate-x-full">
                                    <DialogPanel
                                        class="relative ml-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-12 shadow-xl">
                                        <div class="flex items-center justify-between px-4">
                                            <h2 class="text-lg font-medium text-gray-900">Filters</h2>
                                            <button type="button"
                                                class="-mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400"
                                                @click="mobileFiltersOpen = false">
                                                <span class="sr-only">Close menu</span>
                                                <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                            </button>
                                        </div>

                                        <!-- Filters -->
                                        <form class="mt-4 border-t border-gray-200">
                                            <h3 class="sr-only">Categories</h3>
                                            <ul role="list" class="px-2 py-3 font-medium text-gray-900">
                                                <li v-for="category in subCategories" :key="category.name">
                                                    <a :href="category.href" class="block px-2 py-3">{{ category.name }}</a>
                                                </li>
                                            </ul>

                                            <Disclosure as="div" v-for="section in filters" :key="section.id"
                                                class="border-t border-gray-200 px-4 py-6" v-slot="{ open }">
                                                <h3 class="-mx-2 -my-3 flow-root">
                                                    <DisclosureButton
                                                        class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
                                                        <span class="font-medium text-gray-900">{{ section.name }}</span>
                                                        <span class="ml-6 flex items-center">
                                                            <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                                                            <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                                                        </span>
                                                    </DisclosureButton>
                                                </h3>
                                                <DisclosurePanel class="pt-6">
                                                    <div class="space-y-6">
                                                        <div v-for="(option, optionIdx) in section.options"
                                                            :key="option.value" class="flex items-center">
                                                            <input :id="`filter-mobile-${section.id}-${optionIdx}`"
                                                                :name="`${section.id}[]`" :value="option.value"
                                                                type="checkbox" :checked="option.checked"
                                                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                            <label :for="`filter-mobile-${section.id}-${optionIdx}`"
                                                                class="ml-3 min-w-0 flex-1 text-gray-500">{{ option.label
                                                                }}</label>
                                                        </div>
                                                    </div>
                                                </DisclosurePanel>
                                            </Disclosure>
                                        </form>
                                    </DialogPanel>
                                </TransitionChild>
                            </div>
                        </Dialog>
                    </TransitionRoot>

                    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div class="flex items-baseline justify-between border-b border-gray-200 pb-6 pt-24">
                            <h1 class="text-4xl font-bold tracking-tight text-gray-900">New Arrivals</h1>

                            <div class="flex items-center">
                                <button id="actionsDropdownButton" data-dropdown-toggle="actionsDropdown"
                                    class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                    type="button">
                                    Sort
                                    <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                    </svg>
                                </button>
                                <div id="actionsDropdown"
                                    class="z-10 hidden w-48 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                                    <ul class="space-y-2 text-sm" aria-labelledby="actionsDropdownButton">
                                        <li class="flex items-center">
                                            <button @click="ascprices" 
                                                class="block py-2 px-4 w-full text-left hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white ">Price: Low to High</button>
                                        </li>
                                        <li class="flex items-center">
                                            <button @click="descprices"
                                                class="block py-2 px-4 w-full text-left hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white ">Price: High to Low</button>
                                        </li>
                                        <li class="flex items-center">
                                            <button @click="ascdate"
                                                class="block py-2 px-4 w-full text-left hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white ">Newest First</button>
                                        </li>
                                        <li class="flex items-center">
                                            <button @click="descdate"
                                                class="block py-2 px-4 w-full text-left hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white ">Oldest First</button>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </div>

                        <section aria-labelledby="products-heading" class="pb-24 pt-6">
                            <h2 id="products-heading" class="sr-only">Products</h2>

                            <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
                                <!-- Filters -->
                                <form class="hidden lg:block">
                                    <h3 class="sr-only">Prices</h3>
                                    <!--price filter-->
                                    <div class="flex items-center justify-between space-x-3">
                                        <div class=" basis-1/3">
                                            <label for="filters-price-from"
                                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                                From :
                                            </label>
                                            <input type="number" id="filters-price-from" placeholder="Min Price"
                                                v-model="filterPrices.prices[0]"
                                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900">
                                        </div>
                                        <div class=" basis-1/3">
                                            <label for="filters-price-to"
                                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                                To :
                                            </label>
                                            <input type="number" id="filters-price-to" placeholder="Max Price"
                                                v-model="filterPrices.prices[1]"
                                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900">
                                        </div>
                                        <SecondaryButton class="self-end" @click="productFilter()">
                                            OK</SecondaryButton>
                                    </div>

                                    <!--Brand filter-->
                                    <Disclosure as="div" class="border-b border-gray-200 py-6" v-slot="{ open }">
                                        <h3 class="-my-3 flow-root">
                                            <DisclosureButton
                                                class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                                                <span class="font-medium text-gray-900">Brands</span>
                                                <span class="ml-6 flex items-center">
                                                    <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                                                    <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                                                </span>
                                            </DisclosureButton>
                                        </h3>
                                        <DisclosurePanel class="pt-6">
                                            <div class="space-y-4">
                                                <div v-for="brand in brands" :key="brand.id" class="flex items-center">
                                                    <input :id="`filter-${brand.id}`" :name="`${brand.name}[]`"
                                                        v-model="selectedbrands" :value="brand.name" type="checkbox"
                                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                    <label :for="`filter-${brand.id}`" class="ml-3 text-sm text-gray-600">{{
                                                        brand.name }}</label>
                                                </div>
                                            </div>
                                        </DisclosurePanel>
                                    </Disclosure>

                                    <!--category filter-->
                                    <Disclosure as="div" class="border-b border-gray-200 py-6" v-slot="{ open }">
                                        <h3 class="-my-3 flow-root">
                                            <DisclosureButton
                                                class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                                                <span class="font-medium text-gray-900">Categories</span>
                                                <span class="ml-6 flex items-center">
                                                    <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                                                    <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                                                </span>
                                            </DisclosureButton>
                                        </h3>
                                        <DisclosurePanel class="pt-6">
                                            <div class="space-y-4">
                                                <div v-for="category in categories" :key="category.id"
                                                    class="flex items-center">
                                                    <input :id="`filter-${category.id}${category.name}`"
                                                        :name="`${category.name}[]`" v-model="selectedcategories"
                                                        :value="category.name" type="checkbox"
                                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                    <label :for="`filter-${category.id}${category.name}`"
                                                        class="ml-3 text-sm text-gray-600">{{
                                                            category.name }}</label>
                                                </div>
                                            </div>
                                        </DisclosurePanel>
                                    </Disclosure>
                                    <Link @click="pagereload"
                                        class=" underline block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500"
                                        aria-current="page">Reset</Link>
                                </form>

                                <!-- Product grid -->
                                <div class="lg:col-span-3">
                                    <!-- Your content -->
                                    <productlist :products="products.data">

                                    </productlist>
                                </div>
                            </div>
                        </section>
                    </main>
                </div>
            </div>
        </AdminLayout>
    </div>
</template>