<template>
    <div class="modal-backdrop">
        <div class="modal-content">
            <button class="close-btn" @click="$emit('close')">X</button>
            <AddProduct :categories="categories" @product-added="handleProductAdded" />
        </div>
    </div>
</template>

<script>
import AddProduct from './AddProduct.vue';

export default {
    name: 'AddProductModal',
    props: {
        categories: {
            type: Array,
            required: true
        }
    },
    components: { AddProduct },
    methods: {
        handleProductAdded(product) {
            this.$emit('product-added', product);
            this.$emit('close');
        }
    }
}
</script>

<style scoped>
.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
}

.modal-content {
    background: white;
    padding: 30px;
    border-radius: 10px;
    width: 400px;
    position: relative;
}

.close-btn {
    position: absolute;
    top: 5px;
    right: 10px;
    padding: 5px;
    margin: 1.5px;
    border: none;
    font-size: 10px;
    cursor: pointer;
}
</style>
