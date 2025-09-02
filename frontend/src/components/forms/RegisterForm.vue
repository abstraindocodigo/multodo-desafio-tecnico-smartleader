<template>
  <form @submit.prevent="handleSubmit" class="space-y-4">
    <!-- Name -->
    <div>
      <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
        Nome completo
      </label>
      <input
        id="name"
        v-model="form.name"
        type="text"
        required
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        :disabled="loading"
        placeholder="Seu nome completo"
      />
    </div>

    <!-- Email -->
    <div>
      <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
        Email
      </label>
      <input
        id="email"
        v-model="form.email"
        type="email"
        required
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        :disabled="loading"
        placeholder="seu@email.com"
      />
    </div>

    <!-- Password -->
    <div>
      <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
        Senha
      </label>
      <input
        id="password"
        v-model="form.password"
        type="password"
        required
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        :disabled="loading"
        placeholder="Mínimo 6 caracteres"
      />
    </div>

    <!-- Confirm Password -->
    <div>
      <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
        Confirmar senha
      </label>
      <input
        id="password_confirmation"
        v-model="form.password_confirmation"
        type="password"
        required
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        :disabled="loading"
        placeholder="Digite a senha novamente"
      />
    </div>

    <!-- Company Section -->
    <div class="pt-4 border-t border-gray-200">
      <h3 class="text-sm font-medium text-gray-900 mb-4">Dados da empresa</h3>
      
      <!-- Company Name -->
      <div class="mb-4">
        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">
          Nome da empresa
        </label>
        <input
          id="company_name"
          v-model="form.company_name"
          type="text"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          :disabled="loading"
          placeholder="Nome da sua empresa"
        />
      </div>

      <!-- Company Identifier -->
      <div>
        <label for="company_identifier" class="block text-sm font-medium text-gray-700 mb-1">
          Identificador da empresa
        </label>
        <input
          id="company_identifier"
          v-model="form.company_identifier"
          type="text"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          :disabled="loading"
          placeholder="empresa-abc"
        />
        <p class="mt-1 text-xs text-gray-500">Apenas letras, números e hífens</p>
      </div>
    </div>

    <!-- Submit Button -->
    <button
      type="submit"
      class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
      :disabled="loading"
    >
      <div v-if="loading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
      {{ loading ? 'Criando conta...' : 'Criar conta' }}
    </button>
  </form>
</template>

<script>
export default {
  name: 'RegisterForm',
  props: {
    loading: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        company_name: '',
        company_identifier: ''
      }
    }
  },
  methods: {
    handleSubmit () {
      this.$emit('submit', this.form)
    }
  }
}
</script>
