<template>
  <div>
    <div v-if="isToastVisible" class="toast-notification">
      {{ toastMessage }}
    </div>
    <ProductModal v-if="selectedProduct" :product="selectedProduct" @close="selectedProduct = null"
      @update-quantity="handleUpdateQuantity" />

    <ul class="product-list-grid">
      <li v-for="product in filteredProducts" :key="product.id"
        :class="{ 'unavailable': !product.available, 'clickable': true }" style="margin-bottom: 10px;"
        @click="openProductDetails(product)">
        <div class="product-name">
          {{ product.name }}
        </div>
        <div>
          Quantité disponible : {{ product.quantity }}
        </div>
        <div>
          <span v-for="star in 5" :key="star" @click.stop="setRating(product.id, star)"
            :style="{ cursor: 'pointer', color: star <= product.rate ? 'gold' : 'gray' }">
            ★
          </span>
        </div>
        <button class="secondary-button" @click.stop="deleteProduct(product.id)"> Supprimer</button>
      </li>
    </ul>
  </div>
</template>

<script>
import ProductModal from './ProductModal.vue';

export default {
  name: 'ProductList',
  components: { ProductModal },

  props: {
    filterCategory: {
      type: String,
      default: ''
    },
    products: {
      type: Array,
      required: true
    }
  },

  data() {
    return {
      isToastVisible: false,
      toastMessage: '',
      selectedProduct: null,
    };
  },

  computed: {
    filteredProducts() {
      if (!this.filterCategory) {
        return this.products.map(p => ({
          ...p,
          available: p.quantity > 0
        }));
      }
      return this.products
        .filter(p => p.category === this.filterCategory)
        .map(p => ({
          ...p,
          available: p.quantity > 0
        }));
    }
  },
  methods: {
    showToast(message) {
      this.toastMessage = message;
      this.isToastVisible = true;
      setTimeout(() => {
        this.isToastVisible = false;
        this.toastMessage = '';
      }, 3000);
    },
    openProductDetails(product) {
      this.selectedProduct = product;
    },
    deleteProduct(productId) {
      this.$emit('delete-product', productId);
    },
    handleUpdateQuantity({ id, quantity }) {
      this.$emit('update-quantity', { id, quantity });
      this.selectedProduct = null;
      this.showToast(`Quantité modifiée.`);
    },
    setRating(productId, rating) {
      this.$emit('update-rate', { id: productId, rate: rating });
      this.showToast(`Votre note pour ce produit est enregistrée.`);
    },
  }
}
</script>
<style scoped>
ul {
  padding: 40px;
  margin: 0;
}

.product-list-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 20px;
}

li {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.product-name {
  text-align: center;
  font-size: larger;
  font-weight: 600;

}

.product-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 10px;
  flex-wrap: wrap;
  gap: 10px;
}

.product-actions input[type="number"] {
  width: 70px;
  padding: 5px;
  font-size: 0.9rem;
}
</style>