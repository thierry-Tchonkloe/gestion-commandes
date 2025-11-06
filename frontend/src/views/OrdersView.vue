<template>
  <div class="container mt-5">
    <h3>Mes Commandes</h3>
    <div v-for="order in orders" :key="order.id" class="card my-3 p-3">
      <h5>Commande #{{ order.id }} — {{ order.status }}</h5>
      <ul>
        <li v-for="item in order.items" :key="item.id">
          {{ item.product.name }} (x{{ item.qty }}) — {{ item.unit_price * item.qty }} FCFA
        </li>
      </ul>
      <strong>Total : {{ order.total }} FCFA</strong>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api } from '../services/api'

const orders = ref([])

onMounted(async () => {
  const { data } = await api.get('/orders')
  orders.value = data.data
})
</script>
