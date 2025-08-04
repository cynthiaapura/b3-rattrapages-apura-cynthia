<template>
  <div class="modal-backdrop" @click.self="close">
    <div class="modal-content">
      <h3>{{ product.name }}</h3>
      <p><strong>Description :</strong> {{ product.description }}</p>
      <p><strong>Catégorie :</strong> {{ product.category }}</p>
      <p><strong>Prix :</strong> {{ product.price }} €</p>
      <p><strong>Quantité disponible :</strong> {{ product.quantity }}</p>
      <p><strong>Date d'expiration :</strong> {{ product.expirationDate }}</p>
      <p><strong>Date d'ajout :</strong> {{ product.addedDate }}</p>
      <button @click="close">Fermer</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductModal',
  props: {
    product: Object
  },
  methods: {
    close() {
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
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  padding: 24px;
  border-radius: 8px;
  width: 400px;
  max-width: 90%;
  text-align: left;
}
</style>
