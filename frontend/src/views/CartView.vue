<template>
  <div class="container mt-5">
    <h3>Mon Panier</h3>
    <table class="table">
      <thead>
        <tr><th>Produit</th><th>Quantité</th><th>Prix</th><th>Total</th><th></th></tr>
      </thead>
      <tbody>
        <tr v-for="item in cart" :key="item.id">
          <td>{{ item.name }}</td>
          <td>{{ item.qty }}</td>
          <td>{{ item.price }}</td>
          <td>{{ item.price * item.qty }}</td>
          <td><button class="btn btn-danger btn-sm" @click="remove(item.id)">X</button></td>
        </tr>
      </tbody>
    </table>

    <h4>Total : {{ total }} FCFA</h4>
    <button class="btn btn-success" @click="checkout">Valider la commande</button>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { api } from '../services/api'
import { useRouter } from 'vue-router'

const router = useRouter()
const cart = ref(JSON.parse(localStorage.getItem('cart') || '[]'))

const total = computed(() => cart.value.reduce((t, i) => t + i.price * i.qty, 0))

function remove(id) {
  cart.value = cart.value.filter(i => i.id !== id)
  localStorage.setItem('cart', JSON.stringify(cart.value))
}

async function checkout() {
  const items = cart.value.map(i => ({ product_id: i.id, qty: i.qty }))
  await api.post('/orders', { items })
  alert('Commande validée avec succès !')
  localStorage.removeItem('cart')
  router.push('/orders')
}
</script>
