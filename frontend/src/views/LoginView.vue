<template>
  <div class="container mt-5">
    <h3>Connexion</h3>
    <form @submit.prevent="login">
      <input v-model="form.email" class="form-control my-2" placeholder="Email">
      <input type="password" v-model="form.password" class="form-control my-2" placeholder="Mot de passe">
      <button class="btn btn-success">Se connecter</button>
    </form>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

const form = reactive({ email: '', password: '' })
const auth = useAuthStore()
const router = useRouter()

async function login() {
  await auth.login(form)
  router.push('/products')
}
</script>
