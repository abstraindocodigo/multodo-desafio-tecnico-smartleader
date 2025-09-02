<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
      <!-- Header -->
      <AuthHeader 
        icon="mdi:lock-reset"
        title="Redefinir senha"
        subtitle="Digite sua nova senha"
      />

      <!-- Reset Password Form -->
      <div class="bg-white py-8 px-6 shadow-sm border border-gray-200 rounded-lg">
        <!-- Success Message -->
        <AlertMessage 
          v-if="success" 
          type="success" 
          :message="successMessage" 
        />

        <!-- Error Message -->
        <AlertMessage 
          v-if="error" 
          type="error" 
          :message="error" 
        />

        <ResetPasswordForm 
          v-if="!success"
          :loading="loading"
          :form-data="form"
          @submit="handleResetPassword"
        />

        <!-- Back to Login -->
        <div class="mt-6 text-center">
          <router-link 
            to="/login" 
            class="text-sm text-blue-600 hover:text-blue-500 transition-colors duration-150"
          >
            ← Voltar para o login
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { authAPI } from '@/api/auth'
import AuthHeader from '@/components/ui/AuthHeader.vue'
import AlertMessage from '@/components/ui/AlertMessage.vue'
import ResetPasswordForm from '@/components/forms/ResetPasswordForm.vue'

export default {
  name: 'ResetPasswordView',
  components: {
    AuthHeader,
    AlertMessage,
    ResetPasswordForm
  },
  data () {
    return {
      form: {
        email: '',
        token: '',
        password: '',
        password_confirmation: ''
      },
      loading: false,
      error: '',
      success: false,
      successMessage: ''
    }
  },
  created () {
    // Extrair email e token da URL
    const urlParams = new URLSearchParams(window.location.search)
    this.form.email = urlParams.get('email') || ''
    this.form.token = urlParams.get('token') || ''
    
    if (!this.form.email || !this.form.token) {
      this.error = 'Link inválido ou expirado'
    }
  },
  methods: {
    async handleResetPassword (formData) {
      this.loading = true
      this.error = ''
      this.success = false

      // Validação básica
      if (formData.password !== formData.password_confirmation) {
        this.error = 'As senhas não coincidem'
        this.loading = false
        return
      }

      if (formData.password.length < 6) {
        this.error = 'A senha deve ter pelo menos 6 caracteres'
        this.loading = false
        return
      }

      try {
        const response = await authAPI.resetPassword({
          ...this.form,
          ...formData
        })
        
        this.success = true
        this.successMessage = response.data.message || 'Senha redefinida com sucesso! Você já pode fazer login com sua nova senha.'
        
        // Redirecionar para login após 3 segundos
        setTimeout(() => {
          this.$router.push('/login')
        }, 3000)
        
      } catch (error) {
        this.error = error.response?.data?.error || 'Erro ao redefinir senha. Verifique se o link não expirou e tente novamente.'
      }

      this.loading = false
    }
  }
}
</script>

<style scoped>
/* Simple fade-in animation */
.bg-white {
  animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
