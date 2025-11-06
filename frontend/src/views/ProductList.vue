<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
      <input v-model="search" @input="fetchProducts" class="form-control w-50" placeholder="Rechercher...">
      <router-link to="/cart" class="btn btn-primary">🛒 Voir Panier ({{ cart.length }})</router-link>
    </div>

    <div class="row mt-3">
      <div v-for="p in products" :key="p.id" class="col-md-4 mb-3">
        <div class="card p-3">
          <h5>{{ p.name }}</h5>
          <p>Prix : {{ p.price }} FCFA</p>
          <button class="btn btn-outline-primary" @click="addToCart(p)">Ajouter au panier</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { api } from '../services/api'

const products = ref([])
const search = ref('')
const cart = JSON.parse(localStorage.getItem('cart') || '[]')

async function fetchProducts() {
  const { data } = await api.get('/products', { params: { search: search.value } })
  products.value = data.data
}

function addToCart(product) {
  const existing = cart.find(i => i.id === product.id)
  if (existing) existing.qty++
  else cart.push({ ...product, qty: 1 })
  localStorage.setItem('cart', JSON.stringify(cart))
}

fetchProducts()
</script>
