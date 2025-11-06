<template>
  <div class="container mt-5">
    <h3>Inscription</h3>
    <form @submit.prevent="register">
      <input v-model="form.name" class="form-control my-2" placeholder="Nom">
      <input v-model="form.email" class="form-control my-2" placeholder="Email">
      <input type="password" v-model="form.password" class="form-control my-2" placeholder="Mot de passe">
      <input type="password" v-model="form.password_confirmation" class="form-control my-2" placeholder="Confirmer">
      <button class="btn btn-primary">S'inscrire</button>
    </form>
  </div>
</template>

<script setup>
  import { reactive } from 'vue'
  import { useAuthStore } from '../stores/auth'
  import { useRouter } from 'vue-router'

  const form = reactive({ name: '', email: '', password: '', password_confirmation: '' })
  const auth = useAuthStore()
  const router = useRouter()

  async function register() {
    await auth.register(form)
    await auth.login({ email: form.email, password: form.password })
    router.push('/products')
  }
</script>
