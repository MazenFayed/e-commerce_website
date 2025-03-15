<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import productlist from './ProductList.vue'

defineProps({
    products:Array
    
})


</script>
<template>

<AdminLayout>    
    <productlist  :products="products.data"  >
        
    </productlist>
</AdminLayout>

</template>

