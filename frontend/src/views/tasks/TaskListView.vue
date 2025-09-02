<template>
  <div class="space-y-6">
    <!-- Header com botões -->
    <div class="flex justify-between items-center">
      <h2 class="text-xl font-bold text-gray-900">Minhas Tarefas</h2>
      <div class="flex gap-3">
        <button @click="exportTasks" :disabled="loading"
          class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
          <Icon icon="lucide:download" class="mr-2" />
          Exportar CSV
        </button>
        <router-link to="/dashboard/task/new"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
          <Icon icon="lucide:plus-circle" class="mr-2" />
          Nova Tarefa
        </router-link>
      </div>
    </div>

    <!-- Filtros -->
    <TaskFilters :filters="filters" @apply-filters="applyFilters" @clear-filters="clearFilters" />

    <!-- Loading -->
    <LoadingSpinner v-if="loading" message="Carregando tarefas..." />

    <!-- Lista de Tarefas -->
    <div v-else-if="tasks.length > 0">
      <TaskGrid :tasks="tasks" @delete-task="deleteTask" />

      <!-- Paginação -->
      <PaginationComponent v-if="pagination.total > 0" :current-page="pagination.currentPage"
        :total-pages="pagination.totalPages" :total="pagination.total" :from="pagination.from" :to="pagination.to"
        @prev-page="prevPage" @next-page="nextPage" @go-to-page="goToPage" />
    </div>

    <!-- Empty State -->
    <EmptyState v-else title="Nenhuma tarefa encontrada"
      message="Você ainda não tem tarefas ou os filtros não retornaram resultados." action-text="Criar Primeira Tarefa"
      action-to="/dashboard/task/new" />
  </div>
</template>

<script>
import TaskFilters from '@/components/tasks/TaskFilters.vue'
import TaskGrid from '@/components/tasks/TaskGrid.vue'
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import PaginationComponent from '@/components/ui/PaginationComponent.vue'
import { taskStore } from '@/stores/taskStore'
import { Icon } from '@iconify/vue2'

export default {
  name: 'TaskListView',
  components: {
    TaskFilters,
    TaskGrid,
    LoadingSpinner,
    EmptyState,
    PaginationComponent,
    Icon
  },
  data() {
    return {
      filters: {
        status: '',
        priority: '',
        user_id: ''
      }
    }
  },
  computed: {
    tasks() {
      return taskStore.tasks
    },
    loading() {
      return taskStore.loading
    },
    pagination() {
      return taskStore.pagination
    }
  },
  async mounted() {
    // Carregar tasks na inicialização
    await taskStore.loadTasks()
  },
  methods: {
    async applyFilters(filters) {
      this.filters = { ...filters }
      taskStore.setFilters(this.filters)
      // Reset para página 1 ao aplicar filtros
      await taskStore.loadTasks(1)
    },

    clearFilters() {
      this.filters = { status: '', priority: '', user_id: '' }
      taskStore.clearFilters()
      // Reset para página 1 ao limpar filtros
      taskStore.loadTasks(1)
    },

    async deleteTask(id) {
      if (confirm('Tem certeza que deseja excluir esta tarefa?')) {
        await taskStore.deleteTask(id)
      }
    },

    // Métodos de paginação
    async prevPage() {
      try {
        await taskStore.prevPage()
      } catch (error) {
        console.error('Erro ao navegar para página anterior:', error)
      }
    },

    async nextPage() {
      try {
        await taskStore.nextPage()
      } catch (error) {
        console.error('Erro ao navegar para próxima página:', error)
      }
    },

    async goToPage(page) {
      try {
        await taskStore.goToPage(page)
      } catch (error) {
        console.error('Erro ao navegar para página:', error)
      }
    },

    async exportTasks() {
      try {
        const result = await taskStore.exportTasks()
        if (result && result.success) {
          // Mostrar mensagem de sucesso
          this.$toast?.success?.(result.message) || alert(result.message)
        } else if (result && !result.success) {
          // Mostrar mensagem de erro
          this.$toast?.error?.(result.message) || alert(result.message)
        }
      } catch (error) {
        console.error('Erro ao exportar tarefas:', error)
        this.$toast?.error?.('Erro ao exportar tarefas. Tente novamente.') || alert('Erro ao exportar tarefas. Tente novamente.')
      }
    }
  }
}
</script>
