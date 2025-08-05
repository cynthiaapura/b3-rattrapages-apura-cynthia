<template>
  <div class="modal-backdrop" @click.self="close">
    <div class="modal-content">
      <h3>{{ product.name }}</h3>
      <p><strong>Description :</strong> {{ product.description }}</p>
      <p><strong>Catégorie :</strong> {{ product.category }}</p>
      <p><strong>Prix :</strong> {{ product.price }} €</p>
      <p>
        <strong>Quantité disponible :</strong>
        <span v-if="!isEditing">{{ product.quantity }}</span>
        <input v-else type="number" min="0" required v-model.number="localQuantity"
          @keydown.enter.prevent="validateQuantity" @keydown.esc.prevent="cancelEditing" ref="quantityInput" />
        <button @click="startEditing" v-if="!isEditing">Modifier</button>
      </p>
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
  data() {
    return {
      isEditing: false,
      localQuantity: this.product.quantity,
      isDirty: false,
    };
  },
  methods: {
    startEditing() {
      this.isEditing = true;
      this.localQuantity = this.product.quantity;
      this.isDirty = false;
      this.$nextTick(() => {
        this.$refs.quantityInput.focus();
      });
    },
    validateQuantity() {
      if (this.localQuantity === null || this.localQuantity < 0) {
        alert('La quantié ne peut pas être négative.');
        this.$refs.quantityInput.focus();
        return;
      }

      if (this.localQuantity !== this.product.quantity) {
        this.$emit('update-quantity', { id: this.product.id, quantity: this.localQuantity });
      }
      this.isEditing = false;
    },
    cancelEditing() {
      this.isEditing = false;
      this.localQuantity = this.product.quantity;
      this.isDirty = false;
    },
    close() {
      if (this.isEditing) {
        alert('Vous avez des modifications non enregistrées. Veuillez valider avec Entrée ou annuler la modification.');
        this.$refs.quantityInput.focus();
        return;
      }
      this.$emit('close');
    }
  },
  watch: {
    product(newProduct) {
      this.localQuantity = newProduct.quantity;
      this.isEditing = false;
      this.isDirty = false;
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
