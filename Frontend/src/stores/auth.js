import { defineStore } from 'pinia'

export const useAuth = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('token'),
    user: JSON.parse(localStorage.getItem('user') || '{}'),
  }),

  getters: {
    isLoggedIn: (state) => !!state.token,
    isCustomer: (state) => state.user?.role === 'customer',
    isAdmin: (state) => state.user?.role === 'admin',
    userName: (state) => state.user?.name || 'Customer',
    userEmail: (state) => state.user?.email || '',
    userInitial: (state) => {
      const name = state.user?.name || 'Customer'
      return name.charAt(0).toUpperCase()
    },
  },

  actions: {
    setAuth(token, user) {
      this.token = token
      this.user = user
      localStorage.setItem('token', token)
      localStorage.setItem('user', JSON.stringify(user))
    },

    clearAuth() {
      this.token = null
      this.user = {}
      localStorage.removeItem('token')
      localStorage.removeItem('user')
    },

    refresh() {
      this.token = localStorage.getItem('token')
      try {
        this.user = JSON.parse(localStorage.getItem('user') || '{}')
      } catch {
        this.user = {}
      }
    },
  },
})
