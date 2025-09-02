<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <!-- Title -->
    <div>
      <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
        Título <span class="text-red-500">*</span>
      </label>
      <input
        id="title"
        v-model="form.title"
        type="text"
        class="input-field"
        required
        :disabled="loading"
        placeholder="Digite o título da tarefa"
      />
    </div>

    <!-- Description -->
    <div>
      <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
        Descrição
      </label>
      <textarea
        id="description"
        v-model="form.description"
        class="input-field resize-none"
        rows="4"
        :disabled="loading"
        placeholder="Descreva a tarefa (opcional)"
      ></textarea>
    </div>

    <!-- Status and Priority -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
          Status
        </label>
        <select
          id="status"
          v-model="form.status"
          class="input-field"
          :disabled="loading"
        >
          <option value="pendente">Pendente</option>
          <option value="em_andamento">Em Andamento</option>
          <option value="concluida">Concluída</option>
        </select>
      </div>

      <div>
        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
          Prioridade
        </label>
        <select
          id="priority"
          v-model="form.priority"
          class="input-field"
          :disabled="loading"
        >
          <option value="baixa">Baixa</option>
          <option value="media">Média</option>
          <option value="alta">Alta</option>
        </select>
      </div>
    </div>

    <!-- Due Date -->
    <div>
      <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
        Data Limite
      </label>
      <input
        id="due_date"
        v-model="form.due_date"
        type="datetime-local"
        class="input-field"
        :disabled="loading"
      />
    </div>

    <!-- Form Actions -->
    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
      <button
        type="submit"
        class="btn-primary flex-1 inline-flex items-center justify-center"
        :disabled="loading"
      >
        <div v-if="loading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
        <Icon v-else :icon="isEditing ? 'mdi:content-save' : 'mdi:plus'" class="mr-2" />
        {{ loading ? 'Salvando...' : (isEditing ? 'Atualizar Tarefa' : 'Criar Tarefa') }}
      </button>

      <router-link 
        to="/dashboard" 
        class="btn-secondary flex-1 inline-flex items-center justify-center"
      >
        <Icon icon="mdi:close" class="mr-2" />
        Cancelar
      </router-link>
    </div>
  </form>
</template>

<script>
import { Icon } from '@iconify/vue2'

export default {
  name: 'TaskForm',
  components: {
    Icon
  },
  props: {
    isEditing: {
      type: Boolean,
      default: false
    },
    loading: {
      type: Boolean,
      default: false
    },
    initialData: {
      type: Object,
      default: () => ({
        title: '',
        description: '',
        status: 'pendente',
        priority: 'media',
        due_date: ''
      })
    }
  },
  data () {
    return {
      form: {
        title: '',
        description: '',
        status: 'pendente',
        priority: 'media',
        due_date: ''
      }
    }
  },
  watch: {
    initialData: {
      handler (newData) {
        this.form = { ...newData }
      },
      immediate: true,
      deep: true
    }
  },
  methods: {
    handleSubmit () {
      this.$emit('submit', this.form)
    }
  }
}
</script>
