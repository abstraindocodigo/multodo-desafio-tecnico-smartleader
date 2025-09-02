import Vue from 'vue'
import { companyService } from '@/services/companyService'

const state = Vue.observable({
  companyUsers: [],
  companyStats: {
    totalUsers: 0,
    totalTasks: 0,
    completedTasks: 0,
    pendingTasks: 0,
    inProgressTasks: 0
  },
  userTasks: {},
  loading: false,
  filters: {
    user_id: '',
    status: '',
    priority: ''
  }
})

export const companyStore = {
  // Getters
  get companyUsers() { return Array.isArray(state.companyUsers) ? state.companyUsers : [] },
  get companyStats() { return state.companyStats },
  get userTasks() { return state.userTasks },
  get loading() { return state.loading },
  get filters() { return state.filters },
  
  // Computed getters
  get totalUsers() { return state.companyStats.totalUsers },
  get totalTasks() { return state.companyStats.totalTasks },
  get completedTasks() { return state.companyStats.completedTasks },
  get pendingTasks() { return state.companyStats.pendingTasks },
  get inProgressTasks() { return state.companyStats.inProgressTasks },
  
  // Actions
  async loadCompanyUsers() {
    state.loading = true
    try {
      const response = await companyService.getCompanyUsers()
      if (response && response.data) {
        state.companyUsers = Array.isArray(response.data) ? response.data : []
        state.companyStats.totalUsers = state.companyUsers.length
      } else {
        state.companyUsers = []
        state.companyStats.totalUsers = 0
      }
    } catch (error) {
      console.error('Erro ao carregar usuários da empresa:', error)
      state.companyUsers = []
      state.companyStats.totalUsers = 0
    } finally {
      state.loading = false
    }
  },

  async loadCompanyStats() {
    state.loading = true
    try {
      const response = await companyService.getCompanyStats()
      if (response && response.data) {
        state.companyStats = {
          ...state.companyStats,
          ...response.data
        }
      }
    } catch (error) {
      console.error('Erro ao carregar estatísticas da empresa:', error)
    } finally {
      state.loading = false
    }
  },

  async loadUserTasks(userId) {
    state.loading = true
    try {
      const response = await companyService.getUserTasks(userId)
      if (response && response.data) {
        state.userTasks[userId] = Array.isArray(response.data) ? response.data : []
      } else {
        state.userTasks[userId] = []
      }
    } catch (error) {
      console.error(`Erro ao carregar tasks do usuário ${userId}:`, error)
      state.userTasks[userId] = []
    } finally {
      state.loading = false
    }
  },

  async loadAllUsersTasks() {
    state.loading = true
    try {
      const response = await companyService.getAllUsersTasks()
      if (response && response.data) {
        // Organizar tasks por usuário
        const tasksByUser = {}
        const allTasks = Array.isArray(response.data) ? response.data : []
        
        allTasks.forEach(task => {
          const userId = task.user_id
          if (!tasksByUser[userId]) {
            tasksByUser[userId] = []
          }
          tasksByUser[userId].push(task)
        })
        
        state.userTasks = tasksByUser
        
        // Calcular estatísticas
        state.companyStats.totalTasks = allTasks.length
        state.companyStats.completedTasks = allTasks.filter(task => task.status === 'concluida').length
        state.companyStats.pendingTasks = allTasks.filter(task => task.status === 'pendente').length
        state.companyStats.inProgressTasks = allTasks.filter(task => task.status === 'em_andamento').length
      }
    } catch (error) {
      console.error('Erro ao carregar tasks de todos os usuários:', error)
      state.userTasks = {}
      state.companyStats.totalTasks = 0
      state.companyStats.completedTasks = 0
      state.companyStats.pendingTasks = 0
      state.companyStats.inProgressTasks = 0
    } finally {
      state.loading = false
    }
  },

  getUserTasks(userId) {
    return state.userTasks[userId] || []
  },

  getUserTaskStats(userId) {
    const userTasks = this.getUserTasks(userId)
    return {
      total: userTasks.length,
      completed: userTasks.filter(task => task.status === 'concluida').length,
      pending: userTasks.filter(task => task.status === 'pendente').length,
      inProgress: userTasks.filter(task => task.status === 'em_andamento').length
    }
  },

  setFilters(filters) {
    Object.assign(state.filters, filters)
  },

  clearFilters() {
    state.filters = { user_id: '', status: '', priority: '' }
  },

  clearData() {
    state.companyUsers = []
    state.companyStats = {
      totalUsers: 0,
      totalTasks: 0,
      completedTasks: 0,
      pendingTasks: 0,
      inProgressTasks: 0
    }
    state.userTasks = {}
  }
}

export default companyStore
