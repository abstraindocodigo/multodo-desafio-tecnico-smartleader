<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Global Loader -->
    <GlobalLoader 
      :loading="isLoading" 
      message="Carregando dados do dashboard..." 
    />

    <!-- Stats Cards -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <StatsCards :stats="taskStats" />

      <!-- Main Content -->
      <div class="bg-white rounded-lg border border-gray-200">
        <div class="p-6">
          <router-view />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import StatsCards from '@/components/ui/StatsCards.vue'
import GlobalLoader from '@/components/ui/GlobalLoader.vue'
import { taskStore } from '@/stores/taskStore'
import { authStore } from '@/stores/authStore'

export default {
  name: 'DashboardView',
  components: {
    StatsCards,
    GlobalLoader,
  },
  computed: {
    isLoading () {
      return taskStore.loading
    },
    user () {
      return authStore.user
    },
    userCompany () {
      return authStore.userCompany
    },
    totalTasks () {
      return taskStore.totalTasks
    },
    completedTasks () {
      return taskStore.completedTasks
    },
    pendingTasks () {
      return taskStore.pendingTasks
    },
    taskStats () {
      return [
        {
          label: 'Total',
          value: this.totalTasks,
          color: 'blue',
          icon: 'mdi:clipboard-list'
        },
        {
          label: 'Concluídas',
          value: this.completedTasks,
          color: 'green',
          icon: 'mdi:check-circle'
        },
        {
          label: 'Pendentes',
          value: this.pendingTasks,
          color: 'yellow',
          icon: 'mdi:clock-outline'
        }
      ]
    }
  },
  async created () {
    await taskStore.loadTasks()
  },
  watch: {
    '$route'(to, from) {
      if (to.path === '/dashboard') {
        taskStore.loadTasks()
      }
    }
  },
  methods: {
    async handleLogout () {
      await authStore.logout()
    }
  }
}
</script>

<style scoped>
.bg-white {
  animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(5px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
