<template>
  <div id="app">
    <h2>Bienvenue sur votre espace</h2>
    <CategoryFilter 
      :categories="categories" 
      @categorySelected="selectedCategory = $event"
    />
    <ProductList :filterCategory="selectedCategory" />
  </div>
</template>

<script>
import CategoryFilter from './components/CategoryFilter.vue';
import ProductList from './components/ProductList.vue'

export default {
  name: 'App',
  components: {
    CategoryFilter, ProductList,
  },
  data() {
    return {
      selectedCategory: '',
      categories: ['Plats cuisinés', 'Légumes', 'Desserts', 'Poissons', 'Viennoiseries', 'Fruits']
    };
  }
}

</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
