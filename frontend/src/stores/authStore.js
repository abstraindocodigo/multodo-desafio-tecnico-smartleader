import Vue from 'vue'
import { authAPI } from '@/api/auth'
import router from '@/router'

const state = Vue.observable({
  user: null,
  token: localStorage.getItem('token'),
  isAuthenticated: false
})

export const authStore = {
  // Getters
  get user() { return state.user },
  get token() { return state.token },
  get isLoggedIn() { return !!state.token && state.isAuthenticated },
  get userCompany() { return state.user?.company },
  get isCompanyAdmin() { return state.user?.is_company_admin || false },
  
  // Actions
  async login(credentials) {
    try {
      console.log('Login iniciado')
      const response = await authAPI.login(credentials)
      const { user, token } = response.data

      state.user = user
      state.token = token
      state.isAuthenticated = true

      localStorage.setItem('token', token)
      localStorage.setItem('user', JSON.stringify(user))
      router.push({ path: '/dashboard' })
      return { success: true, data: response.data }
    } catch (error) {
      return {
        success: false,
        error: error.response?.data?.error || 'Erro ao fazer login'
      }
    }
  },

  async register(userData) {
    try {
      const response = await authAPI.register(userData)
      const { user, message } = response.data      
      return { 
        success: true, 
        data: response.data,
        message: message || 'Conta criada com sucesso! Verifique seu email para confirmar a conta.'
      }
    } catch (error) {
      return {
        success: false,
        error: error.response?.data || 'Erro ao registrar usuário'
      }
    }
  },

  async logout() {
    try {
      if (state.token) {
        await authAPI.logout()
      }
    } catch (error) {
      console.error('Erro ao fazer logout:', error)
    } finally {
      state.user = null
      state.token = null
      state.isAuthenticated = false

      localStorage.removeItem('token')
      localStorage.removeItem('user')
      
      router.push({ path: '/login' })
    }
  },

  async fetchUser() {
    try {
      if (!state.token) return

      const response = await authAPI.me()
      state.user = response.data.user
      state.isAuthenticated = true

      localStorage.setItem('user', JSON.stringify(response.data.user))
    } catch (error) {
      console.error('Erro ao buscar dados do usuário:', error)
      this.logout()
    }
  },

  async verifyEmail(verificationUrl) {
    try {
      // Se a URL já contém o domínio completo, usar diretamente
      let fullUrl = verificationUrl
      if (!verificationUrl.startsWith('http')) {
        fullUrl = `http://localhost:8000${verificationUrl}`
      }

      const response = await fetch(fullUrl, {
        method: 'GET',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        }
      })

      const data = await response.json()

      if (response.ok) {
        return {
          success: true,
          message: data.message
        }
      } else {
        return {
          success: false,
          error: data.error || 'Erro ao verificar email'
        }
      }
    } catch (error) {
      console.error('Erro na verificação de email:', error)
      return {
        success: false,
        error: 'Erro de conexão ao verificar email'
      }
    }
  },

  initializeAuth() {
    const token = localStorage.getItem('token')
    const user = localStorage.getItem('user')

    if (token && user) {
      state.token = token
      state.user = JSON.parse(user)
      state.isAuthenticated = true
      this.fetchUser()
    }
  }
}

export default authStore
