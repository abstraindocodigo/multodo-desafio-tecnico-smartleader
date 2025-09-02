<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
      <!-- Header -->
      <AuthHeader 
        icon="mdi:key"
        title="Recuperar senha"
        subtitle="Digite seu email para receber um link de recuperação"
      />

      <!-- Forgot Password Form -->
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

        <ForgotPasswordForm 
          v-if="!success"
          :loading="loading"
          @submit="handleForgotPassword"
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
import ForgotPasswordForm from '@/components/forms/ForgotPasswordForm.vue'

export default {
  name: 'ForgotPasswordView',
  components: {
    AuthHeader,
    AlertMessage,
    ForgotPasswordForm
  },
  data () {
    return {
      loading: false,
      error: '',
      success: false,
      successMessage: ''
    }
  },
  methods: {
    async handleForgotPassword (email) {
      this.loading = true
      this.error = ''
      this.success = false

      try {
        const response = await authAPI.forgotPassword(email)
        
        this.success = true
        this.successMessage = response.data.message || `Email de recuperação enviado para ${email}. Verifique sua caixa de entrada e siga as instruções para redefinir sua senha.`
        
      } catch (error) {
        this.error = error.response?.data?.error || 'Erro ao enviar email de recuperação. Verifique se o email está correto e tente novamente.'
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
