import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/components/Auth/Login.vue'
import HomePage from '@/views/admin/HomePage.vue'
import NotFound from '@/views/NotFound.vue'



const routes = [
  {
    path: '/',
    name: 'home',
    component: HomePage,
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'Not Found!',
    component: NotFound,
  },
  {
    path: '/login',
    name: 'LoginPage',
    component: Login,
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, _, next) => {
  const token = localStorage.getItem('token')
  if (to.meta.requiresAuth && !token) {
    next('/login')
  } else if (to.path === '/login' && token) {
    next('/dashboard')
  } else {
    next()
  }
})

export default router