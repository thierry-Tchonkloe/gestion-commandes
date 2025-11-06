import { defineStore } from 'pinia'
import { api } from '../services/api'
import axios from 'axios'
import type { AxiosError } from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as null | { name: string, email: string },
    token: localStorage.getItem('token') || null,
  }),


  actions: {
    async register(payload: {name: string, email: string, password: string, password_confirmation: string}) {
      try {
        const res = await axios.post('http://localhost:8000/api/register', payload)
        this.user = res.data.user
      } catch (error: unknown) {
        const err = error as AxiosError
        console.error('Erreur d\'inscription', err.response?.data || err.message)
        throw error
      }
    },

    async login(payload: {email: string, password: string;}) {
      try {
        const res = await axios.post('http://localhost:8000/api/login', payload)
        this.token = res.data.access_token
        this.user = res.data.user
        // Sauvegarder le token dans localStorage
        localStorage.setItem('token', this.token)
      } catch (error: unknown) {
        const err = error as AxiosError
        console.error('Erreur de connexion', err.response?.data || err.message)
        throw error
      }
    },

    async logout() {
      await api.post('/logout')
      this.user = null
      this.token = null
      localStorage.removeItem('token')
    },
  },
})
