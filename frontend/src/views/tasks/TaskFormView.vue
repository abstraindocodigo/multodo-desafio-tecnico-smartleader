<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-2xl font-bold text-gray-900">
                {{ isEditing ? 'Editar Tarefa' : 'Nova Tarefa' }}
              </h1>
              <p class="text-gray-600 mt-1">
                {{ isEditing ? 'Atualize os dados da sua tarefa' : 'Preencha os dados para criar uma nova tarefa' }}
              </p>
            </div>
            <router-link 
              to="/dashboard"
              class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
            >
              <span class="mr-2">←</span>
              Voltar
            </router-link>
          </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg border border-gray-200 p-8">
      <!-- Error Alert -->
      <AlertMessage 
        v-if="error" 
        type="error" 
        :message="error" 
      />

      <TaskForm 
        :is-editing="isEditing"
        :loading="loading"
        :initial-data="form"
        @submit="handleSubmit"
      />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AlertMessage from '@/components/ui/AlertMessage.vue'
import TaskForm from '@/components/forms/TaskForm.vue'
import { taskStore } from '@/stores/taskStore'

export default {
  name: 'TaskFormView',
  components: {
    AlertMessage,
    TaskForm
  },
  props: {
    id: {
      type: String,
      default: null
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
      },
      loading: false,
      error: ''
    }
  },
  computed: {
    isEditing () {
      return !!this.id
    },
    currentTask () {
      return taskStore.currentTask
    }
  },
  async mounted () {
    if (this.isEditing) {
      await this.loadTask()
    }
  },
  methods: {
    async loadTask () {
      this.loading = true
      try {
        await taskStore.loadTask(this.id)
        const task = this.currentTask
        this.form = {
          title: task.title,
          description: task.description || '',
          status: task.status,
          priority: task.priority,
          due_date: task.due_date ? this.formatDateForInput(task.due_date) : ''
        }
      } catch (error) {
        this.error = 'Erro ao carregar tarefa'
      }
      this.loading = false
    },

    async handleSubmit (formData) {
      this.loading = true
      this.error = ''

      try {
        const taskData = { ...formData }
        
        if (taskData.due_date) {
          taskData.due_date = new Date(taskData.due_date).toISOString()
        } else {
          delete taskData.due_date
        }

        if (this.isEditing) {
          await taskStore.updateTask(this.id, taskData)
        } else {
          await taskStore.createTask(taskData)
        }

        this.$router.push('/dashboard')
      } catch (err) {
        this.error = 'Erro ao salvar tarefa'
      }

      this.loading = false
    },

    formatDateForInput (dateString) {
      const date = new Date(dateString)
      const year = date.getFullYear()
      const month = String(date.getMonth() + 1).padStart(2, '0')
      const day = String(date.getDate()).padStart(2, '0')
      const hours = String(date.getHours()).padStart(2, '0')
      const minutes = String(date.getMinutes()).padStart(2, '0')

      return `${year}-${month}-${day}T${hours}:${minutes}`
    }
  }
}
</script>

<style scoped>
/* Custom animations */
.card {
  animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
