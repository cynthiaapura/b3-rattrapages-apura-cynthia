<template>
  <div>
    <div v-if="showToast" class="toast-notification">
      {{ toastMessage }}
    </div>
    <ProductModal v-if="selectedProduct" :product="selectedProduct" @close="selectedProduct = null"
      @update-quantity="handleUpdateQuantity" />

    <ul>
      <li v-for="product in filteredProducts" :key="product.id"
        :class="{ 'unavailable': !product.available, 'clickable': true}" style="margin-bottom: 10px;"
        @click="openProductDetails(product)">
        {{ product.name }} - Quantité disponible : {{ product.quantity }}
        <div>
          <span v-for="star in 5" :key="star" @click.stop="setRating(product.id, star)"
            :style="{ cursor: 'pointer', color: star <= product.rate ? 'gold' : 'gray' }">
            ★
          </span>
        </div>
        <button @click.stop="deleteProduct(product.id)"> Supprimer</button>
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
      localProducts: [],
      showToast: false,
      toastMessage: '',
      selectedProduct: null,
    };
  },

  computed: {
    filteredProducts() {
      if (!this.filterCategory) {
        return this.localProducts;
      }
      return this.localProducts.filter(p => p.category === this.filterCategory);
    }
  },

  watch: {
    products: {
      immediate: true,
      handler(newProducts) {
        this.localProducts = [...newProducts.map(p => ({
          ...p,
          available: p.quantity > 0
        }))];
      }
    }
  },

  mounted() {
  },

  methods: {
    setRating(productId, rating) {
      const product = this.localProducts.find(p => p.id === productId);
      if (product) {
        product.rate = rating;
        localStorage.setItem('products', JSON.stringify(this.localProducts));

        this.toastMessage = `Votre note pour "${product.name}" est enregistrée.`;
        this.showToast = true;
        setTimeout(() => {
          this.showToast = false;
          this.toastMessage = '';
        }, 3000);
      }
    },
    openProductDetails(product) {
      this.selectedProduct = product;
    },
    deleteProduct(productId) {
      this.$emit('delete-product', productId);
    },
    startEditing() {
      this.isEditing = true;
      this.localQuantity = this.product.quantity;
      this.isDirty = false;
      this.$nextTick(() => {
        this.$refs.quantityInput.focus();
      });
    },
    handleUpdateQuantity({ id, quantity }) {
      this.$emit('update-quantity', { id, quantity });
      this.selectedProduct = null;
      this.showToast = true;
      this.toastMessage = `Quantité mise à jour pour le produit.`;
      setTimeout(() => {
        this.showToast = false;
        this.toastMessage = '';
      }, 3000);
    }
  }
}
</script>



<style scoped>
.toast-notification {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background-color: #2c3e50;
  color: white;
  padding: 12px 20px;
  border-radius: 4px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
  font-weight: 600;
  z-index: 1000;
  opacity: 0.9;
}

.unavailable {
  opacity: 0.5;
  filter: grayscale(80%);
}

.clickable {
  cursor: pointer;
}
</style>
