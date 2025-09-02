<template>
  <form @submit.prevent="handleSubmit" class="space-y-4">
    <!-- Email -->
    <div>
      <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
        Email
      </label>
      <input id="email" v-model="form.email" type="email" autocomplete="email" required
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        :disabled="loading" placeholder="seu@email.com" />
    </div>

    <!-- Password -->
    <div>
      <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
        Senha
      </label>
      <input id="password" v-model="form.password" type="password" autocomplete="current-password" required
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        :disabled="loading" placeholder="Sua senha" />
    </div>

    <!-- Forgot Password Link -->
    <div class="text-right">
      <router-link to="/forgot-password"
        class="text-sm text-blue-600 hover:text-blue-500 transition-colors duration-150 inline-flex items-center">
        <Icon icon="mdi:key" class="mr-1" />
        Esqueceu sua senha?
      </router-link>
    </div>

    <!-- Submit Button -->
    <button type="submit"
      class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
      :disabled="loading">
      <div v-if="loading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
      {{ loading ? 'Entrando...' : 'Entrar' }}
    </button>
  </form>
</template>

<script>
import { Icon } from '@iconify/vue2'

export default {
  name: 'LoginForm',
  components: {
    Icon
  },
  props: {
    loading: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      form: {
        email: '',
        password: ''
      }
    }
  },
  methods: {
    handleSubmit() {
      this.$emit('submit', this.form)
    }
  }
}
</script>
