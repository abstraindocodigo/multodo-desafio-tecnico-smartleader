import api from './index'

export const authAPI = {
  register (data) {
    return api.post('/auth/register', data)
  },

  login (data) {
    return api.post('/auth/login', data)
  },

  logout () {
    return api.post('/auth/logout')
  },

  me () {
    return api.get('/auth/me')
  },

  refresh () {
    return api.post('/auth/refresh')
  },

  forgotPassword (email) {
    return api.post('/password/forgot', { email })
  },

  resetPassword (data) {
    return api.post('/password/reset', data)
  },

  validateResetToken (email, token) {
    return api.post('/password/validate-token', { email, token })
  }
}
