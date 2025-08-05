<template>
  <div>
    <label for="category-select">Filtrer par catégorie : </label>
    <select id="category-select" @change="onChange" v-model="selectedCategory">
      <option value="">Toutes</option>
      <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
    </select>
  </div>
</template>

<script>
export default {
  name: 'CategoryFilter',
  props: {
    categories: Array
  },
  data() {
    return {
      selectedCategory: ''
    };
  },
  methods: {
    onChange() {
      this.$emit('categorySelected', this.selectedCategory);
    }
  }
}
</script>