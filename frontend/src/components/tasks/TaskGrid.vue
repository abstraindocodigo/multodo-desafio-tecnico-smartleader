<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div
      v-for="task in tasks"
      :key="task.id"
      class="todo-item card-hover p-6 cursor-pointer"
      :class="{
        'border-l-4 border-yellow-400': task.status === 'pendente',
        'border-l-4 border-blue-400': task.status === 'em_andamento',
        'border-l-4 border-green-400': task.status === 'concluida'
      }"
      @click="viewTask(task.id)"
    >
      <!-- Task Header -->
      <div class="flex items-start justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900 flex-1 pr-4">{{ task.title }}</h3>
                  <div class="flex items-center space-x-2" @click.stop>
            <router-link 
              :to="`/dashboard/task/${task.id}/edit`" 
              class="p-2 text-gray-400 hover:text-primary-600 transition-colors duration-200"
              title="Editar tarefa"
            >
              <Icon icon="mdi:pencil" />
            </router-link>
            <button 
              @click="deleteTask(task.id)" 
              class="p-2 text-gray-400 hover:text-red-600 transition-colors duration-200"
              title="Excluir tarefa"
            >
              <Icon icon="mdi:delete" />
            </button>
          </div>
      </div>

      <!-- Task Description -->
      <p v-if="task.description" class="text-gray-600 mb-4 line-clamp-3">{{ task.description }}</p>

      <!-- Task Meta -->
      <div class="flex flex-wrap gap-2 mb-4">
        <span 
          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
          :class="{
            'bg-yellow-100 text-yellow-800': task.status === 'pendente',
            'bg-blue-100 text-blue-800': task.status === 'em_andamento',
            'bg-green-100 text-green-800': task.status === 'concluida'
          }"
        >
          {{ getStatusLabel(task.status) }}
        </span>
        <span 
          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
          :class="{
            'bg-gray-100 text-gray-800': task.priority === 'baixa',
            'bg-orange-100 text-orange-800': task.priority === 'media',
            'bg-red-100 text-red-800': task.priority === 'alta'
          }"
        >
          {{ getPriorityLabel(task.priority) }}
        </span>
      </div>

      <!-- Due Date -->
      <div v-if="task.due_date" class="flex items-center text-sm text-gray-500 mb-4">
        <Icon icon="mdi:calendar" class="mr-2" />
        {{ formatDate(task.due_date) }}
      </div>

      <!-- Task Footer -->
      <div class="border-t border-gray-100 pt-4">
        <p class="text-xs text-gray-500">
          Criada em {{ formatDate(task.created_at) }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import { Icon } from '@iconify/vue2'

export default {
  name: 'TaskGrid',
  components: {
    Icon
  },
  props: {
    tasks: {
      type: Array,
      required: true
    }
  },
  methods: {
    viewTask (id) {
      this.$router.push(`/dashboard/task/${id}`)
    },
    deleteTask (id) {
      this.$emit('delete-task', id)
    },
    getStatusLabel (status) {
      const labels = {
        pendente: 'Pendente',
        em_andamento: 'Em Andamento',
        concluida: 'Concluída'
      }
      return labels[status] || status
    },
    getPriorityLabel (priority) {
      const labels = {
        baixa: 'Baixa',
        media: 'Média',
        alta: 'Alta'
      }
      return labels[priority] || priority
    },
    formatDate (dateString) {
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
/* Line clamp utility for description */
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Custom animations */
.todo-item {
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
