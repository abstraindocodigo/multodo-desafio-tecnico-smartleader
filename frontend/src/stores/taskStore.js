import Vue from 'vue'
import { taskService } from '@/services/taskService'

const state = Vue.observable({
  tasks: [],
  currentTask: null,
  loading: false,
  filters: {
    status: '',
    priority: '',
    user_id: ''
  },
  pagination: {
    currentPage: 1,
    totalPages: 1,
    total: 0,
    perPage: 10,
    from: 0,
    to: 0
  }
})

export const taskStore = {
  // Getters
  get tasks() { return Array.isArray(state.tasks) ? state.tasks : [] },
  get currentTask() { return state.currentTask },
  get loading() { return state.loading },
  get filters() { return state.filters },
  get pagination() { return state.pagination },
  
  // Computed getters
  get totalTasks() { return state.pagination.total },
  get completedTasks() { return Array.isArray(state.tasks) ? state.tasks.filter(task => task.status === 'concluida').length : 0 },
  get pendingTasks() { return Array.isArray(state.tasks) ? state.tasks.filter(task => task.status === 'pendente').length : 0 },
  get inProgressTasks() { return Array.isArray(state.tasks) ? state.tasks.filter(task => task.status === 'em_andamento').length : 0 },
  
  // Pagination getters
  get hasNextPage() { return state.pagination.currentPage < state.pagination.totalPages },
  get hasPrevPage() { return state.pagination.currentPage > 1 },
  get currentPage() { return state.pagination.currentPage },
  get totalPages() { return state.pagination.totalPages },
  
  // Actions
  async loadTasks(page = 1) {
    state.loading = true
    try {
      const cleanFilters = Object.fromEntries(
        Object.entries(state.filters).filter(([key, value]) => value !== '' && value !== null && value !== undefined)
      )
      
      const filtersWithPage = { ...cleanFilters, page }
      
      const response = await taskService.loadTasks(filtersWithPage)
      
      if (response && response.data) {
        const { data, current_page, last_page, total, per_page, from, to } = response
        
        state.tasks = Array.isArray(data) ? data : []
        state.pagination = {
          currentPage: current_page || 1,
          totalPages: last_page || 1,
          total: total || 0,
          perPage: per_page || 10,
          from: from || 0,
          to: to || 0
        }
      } else {
        state.tasks = []
        state.pagination = {
          currentPage: 1,
          totalPages: 1,
          total: 0,
          perPage: 10,
          from: 0,
          to: 0
        }
      }
    } catch (error) {
      console.error('Erro ao carregar tarefas:', error)
      state.tasks = []
      state.pagination = {
        currentPage: 1,
        totalPages: 1,
        total: 0,
        perPage: 10,
        from: 0,
        to: 0
      }
    } finally {
      state.loading = false
    }
  },

  async loadTask(id) {
    state.loading = true
    try {
      const data = await taskService.loadTask(id)
      state.currentTask = data.task
    } catch (error) {
      console.error('Erro ao carregar tarefa:', error)
    } finally {
      state.loading = false
    }
  },

  async createTask(taskData) {
    state.loading = true
    try {
      const data = await taskService.createTask(taskData)
      const task = data.task || data
      const currentTasks = Array.isArray(state.tasks) ? state.tasks : []
      state.tasks = [task, ...currentTasks]
      this.loadTasks()
    } catch (error) {
      console.error('Erro ao criar tarefa:', error)
    } finally {
      state.loading = false
    }
  },

  async updateTask(id, taskData) {
    state.loading = true
    try {
      const data = await taskService.updateTask(id, taskData)
      const updatedTask = data.task
      const index = state.tasks.findIndex(task => task.id === updatedTask.id)
      if (index !== -1) {
        state.tasks.splice(index, 1, updatedTask)
      }
      if (state.currentTask && state.currentTask.id === updatedTask.id) {
        state.currentTask = updatedTask
      }
      this.loadTasks()
    } catch (error) {
      console.error('Erro ao atualizar tarefa:', error)
    } finally {
      state.loading = false
    }
  },

  async deleteTask(id) {
    state.loading = true
    try {
      await taskService.deleteTask(id)
      state.tasks = state.tasks.filter(task => task.id !== id)
      if (state.currentTask && state.currentTask.id === id) {
        state.currentTask = null
      }
      this.loadTasks()
    } catch (error) {
      console.error('Erro ao excluir tarefa:', error)
    } finally {
      state.loading = false
    }
  },

  setFilters(filters) {
    Object.assign(state.filters, filters)
  },

  clearFilters() {
    state.filters = { status: '', priority: '', user_id: '' }
  },

  clearCurrentTask() {
    state.currentTask = null
  },

  async nextPage() {
    if (this.hasNextPage) {
      state.loading = true
      await this.loadTasks(state.pagination.currentPage + 1)
    }
  },

  async prevPage() {
    if (this.hasPrevPage) {
      state.loading = true
      await this.loadTasks(state.pagination.currentPage - 1)
    }
  },

  async goToPage(page) {
    if (page >= 1 && page <= state.pagination.totalPages && page !== state.pagination.currentPage) {
      state.loading = true
      await this.loadTasks(page)
    }
  },

  async exportTasks() {
    state.loading = true
    try {
      const cleanFilters = Object.fromEntries(
        Object.entries(state.filters).filter(([key, value]) => value !== '' && value !== null && value !== undefined)
      )
      
      const response = await taskService.exportTasks(cleanFilters)
      
      if (response && response.download_url) {
        const downloadResponse = await taskService.downloadExport(response.filename)
        
        const blob = new Blob([downloadResponse], { type: 'text/csv;charset=utf-8' })
        const url = window.URL.createObjectURL(blob)
        const link = document.createElement('a')
        link.href = url
        link.download = response.filename
        document.body.appendChild(link)
        link.click()
        document.body.removeChild(link)
        window.URL.revokeObjectURL(url)
        
        return { success: true, message: 'Arquivo exportado com sucesso!' }
      }
    } catch (error) {
      console.error('Erro ao exportar tarefas:', error)
      return { success: false, message: 'Erro ao exportar tarefas. Tente novamente.' }
    } finally {
      state.loading = false
    }
  }
}

export default taskStore
