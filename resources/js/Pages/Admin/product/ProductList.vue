<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import VueSweetalert2 from 'vue-sweetalert2';
import { Plus } from '@element-plus/icons-vue'
import { computed } from 'vue';
import { watch } from 'vue';

defineProps({
    products: Array,
})
const brands = computed(() => usePage().props.brands);
const categories = computed(() => usePage().props.categories);

const isAddProduct = ref(false);
const dialogVisible = ref(false)
const editMode = ref(false);
// upload multiple images
const productImages = ref([]);
const dialogImageUrl = ref('');

console.log('mazen');
const handlefileChange = (file) => {
    productImages.value.push(file);
}
const handleRemove = (file) => {
    console.log(file)
}
const handlePictureCardPreview = (file) => {
    dialogImageUrl.value = file.url
    dialogVisible.value = true
}

//delete images
const deleteImage = async (pimage, index) => {
    try {
        await router.delete('https://localhost/admin/products/image/' + pimage.id, {
            replace: true,
            onSuccess: () => {
                product_images.value.splice(index, 1)
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: usePage().props.flash.success
                });
            }
        });
    } catch (error) {
        console.log("error")
    }
}

//product data
const id = ref('');
const title = ref('');
const price = ref('');
const quantity = ref('');
const description = ref('');
const product_images = ref([]);
const published = ref('');
const category = ref('');
const brand = ref('');
const isStock = ref('');
//end

//open add model
const openAddModal = () => {
    isAddProduct.value = true
    dialogVisible.value = true
    editMode.value = false;
}
//end
//add product method
const AddProduct = () => {
    const formdata = new FormData();
    formdata.append('title', title.value);
    formdata.append('price', price.value);
    formdata.append('quantity', quantity.value);
    formdata.append('description', description.value);
    formdata.append('brand', brand.value);
    formdata.append('category', category.value);
    //append product images
    for (const image of productImages.value) {
        formdata.append('product_images[]', image.raw);
    }
    try {
        router.post("https://localhost/admin/products/store", formdata, {
            replace: true,
            forceFormData: true,
            onSuccess: () => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: usePage().props.flash.success
                });
            },
        });

    } catch (error) {
        console.log(error);
    }

    dialogVisible.value = false;
    resetformData();
    //pagereload();
}

const resetformData = () => {
    id.value = '';
    title.value = '';
    price.value = '';
    quantity.value = '';
    description.value = '';
    productImages.value = '';
    product_images.value = '';
    dialogImageUrl.value = '';
    brand.value = '';
    category.value = '';

}
const pagereload = () => {
    window.location.reload();
}

//end
//open edit model
const openEditModal = (product) => {
    editMode.value = true;
    isAddProduct.value = false
    dialogVisible.value = true;

    //edit product

    id.value = product.id;
    title.value = product.title;
    price.value = product.price;
    quantity.value = product.quantity;
    description.value = product.description;
    brand.value = product.brand;
    category.value = product.category;
    product_images.value = product.product_images;
}
//end

const UpdateProduct = () => {
    const formdata = new FormData();
    formdata.append('id', id.value);
    formdata.append('title', title.value);
    formdata.append('price', price.value);
    formdata.append('quantity', quantity.value);
    formdata.append('description', description.value);
    formdata.append('brand', brand.value);
    formdata.append('category', category.value);
    //append product images
    for (const image of productImages.value) {
        formdata.append('product_images[]', image.raw);
    }
    try {
        router.post("https://localhost/admin/products/update", formdata, {
            replace: true,
            forceFormData: true,
            onSuccess: () => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: usePage().props.flash.success
                });
            },
        });
    } catch (error) {
        console.log(error);
    }

    dialogVisible.value = false;
    resetformData();
    //pagereload();
}

const deleteproduct = (product, index) => {
    Swal.fire({
        title: "Are You Sure ?",
        text: "This action can't be undo !!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes Delete!!",
        cancelButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            try {
                router.delete("https://localhost/admin/products/delete/" + product.id, {
                    replace: true,
                    onSuccess: () => {
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            position: 'top-end',
                            showConfirmButton: false,
                            title: usePage().props.flash.success
                        });
                        this.delete(product, index)
                    }
                });
            } catch (error) {
                console.log(error);
            }
            //pagereload();
        }
    })
}
const input = ref([]);
// brand filter watch
const selectedbrands = ref([]);
// category filter watch
const selectedcategories = ref([]);

watch(input, () => {
    //update the filtered products
    filteredlist();
})
watch(selectedbrands, () => {
    //update the filtered products
    filteredlist();
})
watch(selectedcategories, () => {
    //update the filtered products
    filteredlist();
})
const filteredlist = () => {
    router.visit(route('products.search'), {
        method: 'get',
        preserveState: true,
        replace: true,
        data: {
            path: 'Admin/product/index',
            titles: input.value,
            brands: selectedbrands.value,
            categories: selectedcategories.value,
            admin:'admin',
        }
    })

}
</script>
<template>
    <el-dialog v-model="dialogVisible" :title="editMode ? 'Edit Product' : 'Add Product'" width="30%"
        @close="resetformData">
        <form>
            <div class="sm:col-span-2">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product
                    Title</label>
                <input v-model="title" type="text" name="title" id="title"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Type product title" required="">
            </div>
            <div class="w-full">
                <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                <input v-model="price" type="text" name="price" id="price"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Price" required="">
            </div>
            <div class="w-full">
                <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quantity</label>
                <input v-model="quantity" type="number" name="quantity" id="quantity"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="" required="">
            </div>
            <div>
                <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                <select id="category" v-model="category"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    <option v-for="category in categories" :key="category" :category.name="category">{{ category.name
                    }}
                    </option>
                </select>
            </div>
            <div>
                <label for="Brand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Brand</label>
                <select id="brand" v-model="brand"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    <option v-for="brand in brands" :key="brand" :brand.name="brand">{{ brand.name }}</option>
                </select>
            </div>
            <div class="sm:col-span-2">
                <label for="description"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                <textarea v-model="description" id="description" rows="3"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Your description here"></textarea>
            </div>
            <!-- multiple image upload -->
            <div class="sm:col-span-2">
                <div class="sm:col-span-2">
                    <el-upload v-model:file-list="productImages" list-type="picture-card" multiple
                        :on-preview="handlePictureCardPreview" :on-remove="handleRemove" :on-change="handlefileChange">
                        <i class="el-icon-plus"></i>
                        <el-icon>
                            <Plus />
                        </el-icon>
                    </el-upload>
                </div>
            </div>

            <!--list of images for selected product-->
            <div class="flex flex-nowrap mb-8">
                <div v-for=" (pimage, index) in product_images" :key="pimage.id" class="relative w-32 h-32">
                    <img class="w-24 h-20 rounded" :src="`/${pimage.image}`" alt="">
                    <span @click="deleteImage(pimage, index)"
                        class="absolute top-0 right-8 transform -translate-y-1/2 w-3.5 h-3.5 bg-red-400 border-2 border-white dark:border-gray-800 rounded-full">

                    </span>
                </div>
            </div>


            <button @click="editMode ? UpdateProduct() : AddProduct()" type="submit"
                class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
        </form>
    </el-dialog>

    <main class="p-4 md:ml-64 h-auto pt-20">
        <section class="bg-gray-100 dark:bg-gray-900 p-3 sm:p-5">
            <div class="mx-auto max-w-screen-xxl px-4 lg:px-12">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative w-full shadow-md sm:rounded-lg overflow-hidden">
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="w-full md:w-1/2">
                            <form class="flex items-center">
                                <label for="simple-search" class="sr-only">Search</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                            fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" id="simple-search" v-model="input"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="Search" required="">
                                </div>
                            </form>
                        </div>
                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <button type="button" @click="openAddModal"
                                class="flex items-center justify-center  text-white bg-blue-400 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-400 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 text-center">
                                <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <!--<path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />-->
                                </svg>
                                <el-icon>
                                    <Plus />
                                </el-icon>
                                Add product
                            </button>
                            <div class="flex items-center space-x-3 w-full md:w-auto">
                                <button id="actionsDropdownButton" data-dropdown-toggle="actionsDropdown"
                                    class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                    type="button">
                                    Choose Category
                                    <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                    </svg>
                                </button>
                                <div id="actionsDropdown"
                                    class="z-10 hidden w-48 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                                    <ul class="space-y-2 text-sm" aria-labelledby="actionsDropdownButton">
                                        <li v-for="category in categories" :key="category.id" class="flex items-center">
                                            <input :id="`filter-${category.id}`" :name="`${category.name}[]`"
                                                v-model="selectedcategories" :value="category.name" type="checkbox"
                                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                            <label :for="`filter-${category.id}`" class="ml-3 text-sm text-gray-600">{{
                                                category.name }}</label>
                                        </li>
                                    </ul>

                                </div>
                                <button id="filterDropdownButton" data-dropdown-toggle="filterDropdown"
                                    class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                    type="button">
                                    Choose brand
                                    <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                    </svg>
                                </button>
                                <div id="filterDropdown"
                                    class="z-10 hidden w-48 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                                    <ul class="space-y-2 text-sm" aria-labelledby="filterDropdownButton">
                                        <li v-for="brand in brands" :key="brand.id" class="flex items-center">
                                            <input :id="`filter-${brand.id}`" :name="`${brand.name}[]`"
                                                v-model="selectedbrands" :value="brand.name" type="checkbox"
                                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                            <label :for="`filter-${brand.id}`" class="ml-3 text-sm text-gray-600">{{
                                                brand.name }}</label>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto ">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Product name</th>
                                    <th scope="col" class="px-4 py-3">Category</th>
                                    <th scope="col" class="px-4 py-3">Brand</th>
                                    <th scope="col" class="px-4 py-3">Quantity</th>
                                    <th scope="col" class="px-4 py-3">Price</th>
                                    <th scope="col" class="px-4 py-3">stock</th>
                                    <th scope="col" class="px-4 py-3">published</th>
                                    <th scope="col" class="px-4 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in products" :key="product.id" class="border-b dark:border-gray-700">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{
                                            product.title }}</th>
                                    <td class="px-4 py-3">{{ product.category }}</td>
                                    <td class="px-4 py-3">{{ product.brand }}</td>
                                    <td class="px-4 py-3">{{ product.quantity }}</td>
                                    <td class="px-4 py-3">${{ product.price }}</td>
                                    <td class="px-4 py-3"><span v-if="product.instock == 1"
                                            class="  text-red-100 rounded-md bg-blue-700 p-2 "> Instock</span>
                                        <span v-else class=" text-red-100 rounded-md bg-red-700 p-2 ">OutOfStock</span>
                                    </td>
                                    <td class="px-4 py-3"><span v-if="product.published == 1"
                                            class="  text-red-100 rounded-md bg-blue-700 p-2"> Published</span>
                                        <span v-else class=" text-red-100 rounded-md bg-red-700 p-2">Unpublished</span>
                                    </td>
                                    <td class="px-4 py-3 flex items-center justify-end">
                                        <button :id="`apple-imac-27-dropdown-button-${product.id}`"
                                            :data-dropdown-toggle="`apple-imac-27-dropdown-${product.id}`"
                                            class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                            type="button">
                                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>
                                        <div :id="`apple-imac-27-dropdown-${product.id}`"
                                            class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                                aria-labelledby="apple-imac-27-dropdown-button">

                                                <li>
                                                    <button @click="openEditModal(product)"
                                                        class="block py-2 px-4 w-full text-left hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white ">Edit</button>
                                                </li>
                                            </ul>
                                            <div class="py-1">
                                                <a href="#" @click="deleteproduct(product, index)"
                                                    class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
                        aria-label="Table navigation">
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                            Showing
                            <span class="font-semibold text-gray-900 dark:text-white">1-10</span>
                            of
                            <span class="font-semibold text-gray-900 dark:text-white">1000</span>
                        </span>
                        <ul class="inline-flex items-stretch -space-x-px">
                            <li>
                                <a href="#"
                                    class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <span class="sr-only">Previous</span>
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">1</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">2</a>
                            </li>
                            <li>
                                <a href="#" aria-current="page"
                                    class="flex items-center justify-center text-sm z-10 py-2 px-3 leading-tight text-primary-600 bg-primary-50 border border-primary-300 hover:bg-primary-100 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">3</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">...</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">100</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <span class="sr-only">Next</span>
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </section>
    </main>
</template>