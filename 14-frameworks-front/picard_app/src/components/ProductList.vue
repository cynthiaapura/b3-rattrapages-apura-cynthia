<template>
  <div>
    <h2>Bienvenue sur votre espace</h2>

    <div v-if="showToast" class="toast-notification">
        {{ toastMessage }}
    </div>
    <ul>
      <li v-for="product in products" :key="product.id" style="margin-bottom: 10px;">
        {{ product.name }} - {{ product.price }} €
        <div>
          <span 
            v-for="star in 5" 
            :key="star" 
            @click="setRating(product.id, star)" 
            :style="{ cursor: 'pointer', color: star <= product.rate ? 'gold' : 'gray' }"
          >
            ★
          </span>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'ProductList',
  data() {
    return {
      products: [],
      showToast: false,
      toastMessage: '',
    };
  },
  async mounted() {
    const stored = localStorage.getItem('products');
    if (stored) {
      this.products = JSON.parse(stored);
    } else {
      try {
        const response = await fetch('/products.json');
        const data = await response.json();
        this.products = data;
        localStorage.setItem('products', JSON.stringify(data));
      } catch (error) {
        console.error('Erreur lors du chargement du fichier JSON :', error);
      }
    }
  },
  methods: {
    setRating(productId, rating) {
      const product = this.products.find(p => p.id === productId);
      if (product) {
        product.rate = rating;
        localStorage.setItem('products', JSON.stringify(this.products));

        this.toastMessage = `Votre note pour "${product.name}" est enregistrée.`;
        this.showToast = true;
        setTimeout(() => {
            this.showToast = false;
            this.toastMessage = '';
        }, 3000);
      }
    },
  },
};
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
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
  font-weight: 600;
  z-index: 1000;
  opacity: 0.9;
}
</style>

