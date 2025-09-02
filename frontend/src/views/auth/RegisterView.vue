<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full">
      <AuthHeader icon="mdi:account-plus" title="Criar conta" subtitle="Registre-se para começar a usar o MultiTodo" />

      <div class="bg-white py-8 px-6 shadow-sm border border-gray-200 rounded-lg">
        <AlertMessage v-if="error" type="error" :message="error" />

        <!-- User Registration Form -->
        <div class="space-y-4">
          <UserForm v-model="form" :loading="loading" :companies="companies" />
        </div>

        <!-- Submit Button -->
        <div class="mt-8 pt-6 border-t border-gray-200">
          <button @click="handleRegister" type="button" :disabled="loading"
            class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
            <div v-if="loading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
            {{ loading ? 'Criando conta...' : 'Criar conta' }}
          </button>
        </div>

        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Já tem uma conta?
            <router-link to="/login"
              class="font-medium text-blue-600 hover:text-blue-500 transition-colors duration-150">
              Fazer login
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
import UserForm from '@/components/forms/UserForm.vue'
import { authStore } from '@/stores/authStore'
import { Icon } from '@iconify/vue2'
import axios from 'axios'
export default {
  name: 'RegisterView',
  components: {
    AuthHeader,
    AlertMessage,
    UserForm,
    Icon
  },
  data() {
    return {
      loading: false,
      error: '',
      companies: [],
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        registration_type: 'existing_company',
        existing_company_identifier: ''
      }
    }
  },
  async mounted() {
    await this.loadCompanies()
  },
  methods: {
    async loadCompanies() {
      try {
        const response = await axios.get(`${import.meta.env.VITE_API_BASE_URL}/companies`, {
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        })
        const data = await response.data
        this.companies = data.companies || []
      } catch (error) {
        console.error('Erro ao carregar empresas:', error)
      }
    },

    async handleRegister() {
      this.loading = true
      this.error = ''

      // Basic validation
      if (this.form.password !== this.form.password_confirmation) {
        this.error = 'As senhas não coincidem'
        this.loading = false
        return
      }

      if (!this.form.name || !this.form.email || !this.form.password) {
        this.error = 'Preencha todos os campos obrigatórios'
        this.loading = false
        return
      }

      // Company selection validation
      if (!this.form.existing_company_identifier) {
        this.error = 'Selecione uma empresa'
        this.loading = false
        return
      }

      const result = await authStore.register(this.form)

      if (result.success) {
        this.$router.push({
          path: '/login',
          query: {
            message: result.message || 'Conta criada com sucesso! Verifique seu email para confirmar a conta.'
          }
        })
      } else {
        this.error = typeof result.error === 'string' ? result.error : 'Erro ao registrar usuário'
      }

      this.loading = false
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
