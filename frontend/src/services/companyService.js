import { companyAPI } from '@/api/company'

export const companyService = {
  async getCompanyUsers() {
    const response = await companyAPI.getCompanyUsers()
    return response.data
  },

  async getCompanyStats() {
    const response = await companyAPI.getCompanyStats()
    return response.data
  },

  async getUserTasks(userId) {
    const response = await companyAPI.getUserTasks(userId)
    return response.data
  },

  async getAllUsersTasks() {
    const response = await companyAPI.getAllUsersTasks()
    return response.data
  }
}

export default companyService
