<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
      <AuthHeader icon="mdi:lock" title="MultiTodo" subtitle="Acesse sua conta para continuar" />

      <div class="bg-white py-8 px-6 shadow-sm border border-gray-200 rounded-lg">
        <AlertMessage v-if="error" type="error" :message="error" />
        <AlertMessage v-if="successMessage" type="success" :message="successMessage" />

        <LoginForm :loading="loading" @submit="handleLogin" />

        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Não tem uma conta?
            <router-link to="/register"
              class="font-medium text-blue-600 hover:text-blue-500 transition-colors duration-150">
              Criar conta
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AuthHeader from '@/components/ui/AuthHeader.vue'
import AlertMessage from '@/components/ui/AlertMessage.vue'
import LoginForm from '@/components/forms/LoginForm.vue'
import { authStore } from '@/stores/authStore'

export default {
  name: 'LoginView',
  components: {
    AuthHeader,
    AlertMessage,
    LoginForm
  },
  data() {
    return {
      loading: false,
      error: '',
      successMessage: ''
    }
  },
  mounted() {
    // Verificar se há mensagem de sucesso na query string
    if (this.$route.query.message) {
      this.successMessage = this.$route.query.message
      // Limpar a query string após exibir a mensagem
      this.$router.replace({ path: '/login' })
    }
  },
  methods: {
    async handleLogin(formData) {
      try {
        this.loading = true
        this.error = ''
        const result = await authStore.login(formData)

        if (!result.success) {
          this.error = result.error || 'Erro ao fazer login'
        }
      } catch (error) {
        console.error('Erro no login:', error)
        this.error = error.message || 'Erro inesperado ao fazer login'
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<style scoped>
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
