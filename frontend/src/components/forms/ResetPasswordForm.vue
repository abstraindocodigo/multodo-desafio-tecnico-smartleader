<template>
  <form @submit.prevent="handleSubmit" class="space-y-4">
    <!-- Email (hidden) -->
    <input type="hidden" v-model="formData.email" />
    
    <!-- Password -->
    <div>
      <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
        Nova senha
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
        Confirmar nova senha
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

    <!-- Submit Button -->
    <button
      type="submit"
      class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
      :disabled="loading"
    >
      <div v-if="loading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
      {{ loading ? 'Redefinindo...' : 'Redefinir senha' }}
    </button>
  </form>
</template>

<script>
export default {
  name: 'ResetPasswordForm',
  props: {
    loading: {
      type: Boolean,
      default: false
    },
    formData: {
      type: Object,
      default: () => ({})
    }
  },
  data () {
    return {
      form: {
        password: '',
        password_confirmation: ''
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
