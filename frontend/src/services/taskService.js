import { tasksAPI } from '@/api/tasks'

export const taskService = {
  async loadTasks(filters = {}) {
    const response = await tasksAPI.getTasks(filters)
    return response.data
  },

  async loadTask(id) {
    const response = await tasksAPI.getTask(id)
    return response.data
  },

  async createTask(taskData) {
    const response = await tasksAPI.createTask(taskData)
    return response.data
  },

  async updateTask(id, taskData) {
    const response = await tasksAPI.updateTask(id, taskData)
    return response.data
  },

  async deleteTask(id) {
    await tasksAPI.deleteTask(id)
    return { success: true }
  },

  async exportTasks(filters = {}) {
    const response = await tasksAPI.exportTasks(filters)
    return response.data
  },

  async downloadExport(filename) {
    const response = await tasksAPI.downloadExport(filename)
    return response.data
  }
}

export default taskService
