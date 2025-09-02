import api from './index'

export const tasksAPI = {
  getTasks (params = {}) {
    return api.get('/tasks', { params })
  },

  getTask (id) {
    return api.get(`/tasks/${id}`)
  },

  createTask (data) {
    return api.post('/tasks', data)
  },

  updateTask (id, data) {
    return api.put(`/tasks/${id}`, data)
  },

  deleteTask (id) {
    return api.delete(`/tasks/${id}`)
  },

  exportTasks (filters = {}) {
    return api.post('/export/tasks', filters)
  },

  downloadExport (filename) {
    return api.get(`/export/download/${filename}`, {
      responseType: 'blob'
    })
  }
}
