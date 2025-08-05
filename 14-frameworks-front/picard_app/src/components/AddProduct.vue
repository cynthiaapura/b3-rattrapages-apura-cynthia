<template>
    <div class="add-product-form">
        <h2>Ajouter un nouveau produit</h2>
        <form @submit.prevent="submitForm">
            <div class="form-group">
                <label for="name">Nom du produit</label>
                <input id="name" v-model="name" type="text" required />
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" v-model="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="price">Prix (€)</label>
                <input id="price" v-model.number="price" type="number" step="0.01" min="0" required />
            </div>

            <div class="form-group">
                <label for="quantity">Quantité</label>
                <input id="quantity" v-model.number="quantity" type="number" min="0" required />
            </div>

            <div class="form-group">
                <label for="category">Catégorie</label>
                <select id="category" v-model="category" required>
                    <option disabled value="">-- Sélectionnez une catégorie --</option>
                    <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="expirationDate">Date de péremption</label>
                <input id="expirationDate" v-model="expirationDate" type="date" required />
            </div>

            <button type="submit">Ajouter le produit</button>
        </form>
    </div>
</template>

<script>
export default {
    name: 'AddProduct',
    props: {
        categories: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            name: '',
            description: '',
            price: null,
            quantity: null,
            category: '',
            expirationDate: '',
        };
    },
    methods: {
        submitForm() {
            const newProduct = {
                id: Date.now(),
                name: this.name.trim(),
                description: this.description.trim(),
                price: this.price,
                quantity: this.quantity,
                rate: 0,
                category: this.category.trim(),
                expirationDate: this.expirationDate,
                addedDate: new Date().toISOString().slice(0, 10)
            };

            this.$emit('product-added', newProduct);

            this.name = '';
            this.description = '';
            this.price = null;
            this.quantity = null;
            this.category = '';
            this.expirationDate = '';
        }
    }
};
</script>

<style scoped>
.add-product-form {
    max-width: 400px;
    margin-bottom: 20px;
    padding: 15px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 12px;
}

label {
    display: block;
    font-weight: 600;
    margin-bottom: 4px;
}

input,
textarea {
    width: 100%;
    padding: 6px 8px;
    border-radius: 5px;
    border: 1px solid #ddd;
    font-family: inherit;
    font-size: 1rem;
    color: #333;
    box-sizing: border-box;
}

button {
    width: 100%;
    font-weight: 600;
}
</style>
