<template>
  <div>
    <h2>Bienvenue sur votre espace</h2>
    <ul>
      <li v-for="product in products" :key="product.id">
        {{ product.name }} - {{ product.price }} €
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
  }
};
</script>
