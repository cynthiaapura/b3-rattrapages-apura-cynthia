<template>
  <div id="app">
    <header>
      <img src="./assets/logo.png" alt="Logo Picard" style="height: 50px;" />
      <h2>Bienvenue sur votre espace</h2>
    </header>

    <div class="controls">
      <CategoryFilter :categories="categories" @categorySelected="selectedCategory = $event" />
      <button class="primary-button" @click="showAddModal = true"> Ajouter un produit </button>
    </div>

    <AddProductModal v-if="showAddModal" :categories="categories" @close="showAddModal = false"
      @product-added="addProduct" />

    <ProductList :filterCategory="selectedCategory" :products="products" @delete-product="deleteProduct"
      @update-quantity="updateProductQuantity" @update-rate="updateProductRate" />

    <footer>Picard - Gestion des distributeurs</footer>
  </div>
</template>

<script>
import CategoryFilter from './components/CategoryFilter.vue';
import ProductList from './components/ProductList.vue';
import AddProductModal from './components/AddProductModal.vue';

export default {
  name: 'App',
  components: {
    CategoryFilter, ProductList, AddProductModal,
  },
  data() {
    return {
      selectedCategory: '',
      categories: ['Plats cuisinés', 'Légumes', 'Desserts', 'Poissons', 'Viennoiseries', 'Fruits'],
      products: [],
      showAddModal: false,
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
        console.error('Erreur chargement produits :', error);
      }
    }
  },
  methods: {
    saveProduct() {
      localStorage.setItem('products', JSON.stringify(this.products));
    },
    addProduct(newProduct) {
      this.products = [...this.products, newProduct]
      this.saveProduct();
    },
    deleteProduct(productId) {
      this.products = this.products.filter(p => p.id !== productId);
      this.saveProduct();
    },
    updateProductQuantity({ id, quantity }) {
      this.products = this.products.map(p =>
        p.id === id
          ? { ...p, quantity }
          : p
      );
      this.saveProduct();
    },
    updateProductRate({ id, rate }) {
      this.products = this.products.map(p =>
        p.id === id
          ? { ...p, rate }
          : p
      );
      this.saveProduct();
    },
  }
}
</script>
