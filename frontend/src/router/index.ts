import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import ProductList from '../views/ProductList.vue'
import CartView from '../views/CartView.vue'
import OrdersView from '../views/OrdersView.vue'

const routes = [
  { path: '/', redirect: '/products' },
  { path: '/register', component: RegisterView },
  { path: '/login', component: LoginView },
  { path: '/products', component: ProductList },
  { path: '/cart', component: CartView, meta: { requiresAuth: true } },
  { path: '/orders', component: OrdersView, meta: { requiresAuth: true } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// 🔒 Protection des routes
router.beforeEach((to, from, next) => {
  const auth = useAuthStore()
  if (to.meta.requiresAuth && !auth.token) {
    next('/login')
  } else {
    next()
  }
})

export default router
