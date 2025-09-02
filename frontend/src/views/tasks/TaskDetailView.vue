<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <!-- Header -->
      <div class="mb-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Detalhes da Tarefa</h1>
            <p class="text-gray-600 mt-1">Visualize e gerencie sua tarefa</p>
          </div>
          <div class="flex space-x-3">
            <router-link 
              :to="`/dashboard/task/${task.id}/edit`"
              class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
            >
              <Icon icon="mdi:pencil" class="mr-2" />
              Editar
            </router-link>
            <router-link 
              to="/dashboard"
              class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
            >
              <Icon icon="mdi:arrow-left" class="mr-2" />
              Voltar
            </router-link>
          </div>
        </div>
      </div>

      <!-- Loading -->
      <LoadingSpinner v-if="loading" message="Carregando tarefa..." />

      <!-- Task Details -->
      <div v-else-if="task" class="space-y-6">
        <!-- Main Task Card -->
        <div class="bg-white rounded-lg border border-gray-200 p-8">
          <!-- Task Header -->
          <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
              <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ task.title }}</h2>
              <div class="flex items-center space-x-4">
                <span 
                  class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                  :class="{
                    'bg-yellow-100 text-yellow-800': task.status === 'pendente',
                    'bg-blue-100 text-blue-800': task.status === 'em_andamento',
                    'bg-green-100 text-green-800': task.status === 'concluida'
                  }"
                >
                  {{ getStatusLabel(task.status) }}
                </span>
                <span 
                  class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                  :class="{
                    'bg-gray-100 text-gray-800': task.priority === 'baixa',
                    'bg-orange-100 text-orange-800': task.priority === 'media',
                    'bg-red-100 text-red-800': task.priority === 'alta'
                  }"
                >
                  {{ getPriorityLabel(task.priority) }}
                </span>
              </div>
            </div>
            
            <!-- Mark as Complete Button -->
            <div v-if="task.status !== 'concluida'" class="ml-6">
              <button
                @click="markAsComplete"
                :disabled="loading"
                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <Icon icon="mdi:check" class="mr-2" />
                Marcar como Concluída
              </button>
            </div>
            <div v-else class="ml-6">
              <div class="inline-flex items-center px-6 py-3 border border-green-200 text-base font-medium rounded-md text-green-800 bg-green-50">
                <Icon icon="mdi:check" class="mr-2" />
                Tarefa Concluída
              </div>
            </div>
          </div>

          <!-- Task Description -->
          <div v-if="task.description" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Descrição</h3>
            <div class="prose max-w-none">
              <p class="text-gray-700 whitespace-pre-wrap">{{ task.description }}</p>
            </div>
          </div>

          <!-- Task Meta Information -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Due Date -->
            <div v-if="task.due_date" class="bg-gray-50 rounded-lg p-4">
              <h4 class="text-sm font-medium text-gray-900 mb-2">Data de Vencimento</h4>
              <div class="flex items-center text-gray-700">
                <Icon icon="mdi:calendar" class="mr-2" />
                {{ formatDate(task.due_date) }}
              </div>
            </div>

            <!-- Created Date -->
            <div class="bg-gray-50 rounded-lg p-4">
              <h4 class="text-sm font-medium text-gray-900 mb-2">Data de Criação</h4>
              <div class="flex items-center text-gray-700">
                <Icon icon="mdi:file-document" class="mr-2" />
                {{ formatDate(task.created_at) }}
              </div>
            </div>

            <!-- Updated Date -->
            <div v-if="task.updated_at && task.updated_at !== task.created_at" class="bg-gray-50 rounded-lg p-4">
              <h4 class="text-sm font-medium text-gray-900 mb-2">Última Atualização</h4>
              <div class="flex items-center text-gray-700">
                <Icon icon="mdi:refresh" class="mr-2" />
                {{ formatDate(task.updated_at) }}
              </div>
            </div>

            <!-- Task ID -->
            <div class="bg-gray-50 rounded-lg p-4">
              <h4 class="text-sm font-medium text-gray-900 mb-2">ID da Tarefa</h4>
              <div class="flex items-center text-gray-700">
                <span class="mr-2">#</span>
                {{ task.id }}
              </div>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center">
          <div class="flex space-x-3">
            <button
              @click="deleteTask"
              class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
            >
              <Icon icon="mdi:delete" class="mr-2" />
              Excluir Tarefa
            </button>
          </div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else class="text-center py-12">
        <div class="text-gray-500">
          <p class="text-lg">Tarefa não encontrada</p>
          <router-link 
            to="/dashboard"
            class="text-blue-600 hover:text-blue-500 mt-2 inline-block"
          >
            Voltar para o dashboard
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'
import { taskStore } from '@/stores/taskStore'
import { Icon } from '@iconify/vue2'

export default {
  name: 'TaskDetailView',
  components: {
    LoadingSpinner,
    Icon
  },
  props: {
    id: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      loading: false
    }
  },
  computed: {
    task() {
      return taskStore.currentTask
    }
  },
  async mounted() {
    await this.loadTask()
  },
  methods: {
    async loadTask() {
      this.loading = true
      try {
        await taskStore.loadTask(this.id)
      } catch (error) {
        console.error('Erro ao carregar tarefa:', error)
      } finally {
        this.loading = false
      }
    },

    async markAsComplete() {
      if (confirm('Tem certeza que deseja marcar esta tarefa como concluída?')) {
        this.loading = true
        try {
          await taskStore.updateTask(this.id, { status: 'concluida' })
          // Recarregar a task para atualizar o estado
          await this.loadTask()
        } catch (error) {
          console.error('Erro ao marcar tarefa como concluída:', error)
          alert('Erro ao marcar tarefa como concluída')
        } finally {
          this.loading = false
        }
      }
    },

    async deleteTask() {
      if (confirm('Tem certeza que deseja excluir esta tarefa? Esta ação não pode ser desfeita.')) {
        this.loading = true
        try {
          await taskStore.deleteTask(this.id)
          this.$router.push('/dashboard')
        } catch (error) {
          console.error('Erro ao excluir tarefa:', error)
          alert('Erro ao excluir tarefa')
        } finally {
          this.loading = false
        }
      }
    },

    getStatusLabel(status) {
      const labels = {
        pendente: 'Pendente',
        em_andamento: 'Em Andamento',
        concluida: 'Concluída'
      }
      return labels[status] || status
    },

    getPriorityLabel(priority) {
      const labels = {
        baixa: 'Baixa',
        media: 'Média',
        alta: 'Alta'
      }
      return labels[priority] || priority
    },

    formatDate(dateString) {
      const date = new Date(dateString)
      return date.toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
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
