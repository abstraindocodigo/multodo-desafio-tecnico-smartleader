import api from './index'

export const companyAPI = {
  getCompanyUsers() {
    return api.get('/company/users')
  },

  getCompanyStats() {
    return api.get('/company/stats')
  },

  getUserTasks(userId) {
    return api.get(`/company/users/${userId}/tasks`)
  },

  getAllUsersTasks() {
    return api.get('/company/tasks')
  }
}
