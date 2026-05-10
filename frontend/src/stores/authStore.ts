import { defineStore } from 'pinia'
import { authApi } from '../api/authApi'
import { authService } from '../services/authService'
import type { FitnezUser } from '../types/auth'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as FitnezUser | null,
    loading: false,
    initialized: false,
  }),

  getters: {
    isAuthenticated: (state) => Boolean(state.user),
    isMember: (state) => state.user?.role === 'member',
    isAdmin: (state) => state.user?.role === 'admin',
    isTrainer: (state) => state.user?.role === 'trainer' || Boolean(state.user?.can_access_trainer_workspace),
  },

  actions: {
    async loadMe() {
      this.loading = true

      try {
        const response = await authApi.me()
        this.user = response.data
      } catch {
        this.user = null
        localStorage.removeItem('fitnez_access_token')
      } finally {
        this.loading = false
        this.initialized = true
      }
    },

    async memberLogin(email: string, password: string) {
      this.loading = true

      try {
        const result = await authService.memberLogin(email, password)
        this.user = result.user
      } finally {
        this.loading = false
      }
    },

    async logout() {
      await authService.logout()
      this.user = null
      this.initialized = true
    },
  },
})
