<template>
  <div id="app" class="min-h-screen bg-gray-50">
    <nav v-if="isLoggedIn" class="bg-white border-b border-gray-200 sticky top-0 z-50">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-14">
          <router-link to="/dashboard"
            class="flex items-center space-x-2 hover:opacity-80 transition-opacity duration-150">
            <div class="w-7 h-7 bg-blue-600 rounded-md flex items-center justify-center">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                </path>
              </svg>
            </div>
            <h1 class="text-lg font-semibold text-gray-900">{{ user?.company?.name }}</h1>
          </router-link>
          <div class="relative">
            <button @click="toggleUserDropdown"
              class="flex items-center space-x-2 text-sm text-gray-600 hover:text-gray-900 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 rounded-md px-2 py-1">
              <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-medium">
                  {{ user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                </span>
              </div>
              <span class="hidden sm:block">{{ user?.name }}</span>
              <Icon icon="lucide:chevron-down" class="w-4 h-4 transition-transform duration-150"
                :class="{ 'rotate-180': showUserDropdown }" />
            </button>

            <div v-if="showUserDropdown" @mouseleave="closeUserDropdown"
              class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 py-1 z-50">
              <router-link v-if="authStore.isCompanyAdmin" to="/company/dashboard" @click="closeUserDropdown"
                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                <Icon icon="lucide:building" class="w-4 h-4 mr-3" />
                <span v-if="authStore.isCompanyAdmin" class="text-sm text-nowrap">Gerenciar Empresa</span>
              </router-link>
              <router-link to="/profile" @click="closeUserDropdown"
                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                <Icon icon="lucide:user" class="w-4 h-4 mr-3" />
                Minha Conta
              </router-link>
              <button @click="logout"
                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                <Icon icon="lucide:log-out" class="w-4 h-4 mr-3" />
                Sair
              </button>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <main class="flex-1">
      <router-view />
    </main>
  </div>
</template>

<script>
import { authStore } from '@/stores/authStore'
import { Icon } from '@iconify/vue2'
export default {
  name: 'App',
  components: {
    Icon
  },
  data() {
    return {
      showUserDropdown: false,
      clickOutsideHandler: null
    }
  },
  computed: {
    isLoggedIn() {
      return authStore.isLoggedIn
    },
    user() {
      return authStore.user
    },
    authStore() {
      return authStore
    }
  },
  methods: {
    toggleUserDropdown() {
      this.showUserDropdown = !this.showUserDropdown
    },
    closeUserDropdown() {
      this.showUserDropdown = false
    },
    logout() {
      this.closeUserDropdown()
      authStore.logout()
    }
  },
  mounted() {
    this.clickOutsideHandler = (event) => {
      if (!this.$el.contains(event.target)) {
        this.showUserDropdown = false
      }
    }
    document.addEventListener('click', this.clickOutsideHandler)
  },
  beforeDestroy() {
    if (this.clickOutsideHandler) {
      document.removeEventListener('click', this.clickOutsideHandler)
    }
  }
}
</script>

<style>
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

* {
  transition: all 0.15s ease-in-out;
}
</style>
