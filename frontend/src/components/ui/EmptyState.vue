<template>
  <div class="text-center py-12">
    <div class="card p-8">
      <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <Icon icon="mdi:clipboard-text" class="text-4xl text-gray-400" />
      </div>
      <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ title }}</h3>
      <p class="text-gray-600 mb-6">{{ message }}</p>
      <router-link v-if="actionTo" :to="actionTo" class="btn-primary inline-flex items-center">
        <Icon icon="mdi:plus" class="mr-2" />
        {{ actionText }}
      </router-link>
    </div>
  </div>
</template>

<script>
import { Icon } from '@iconify/vue2'

export default {
  name: 'EmptyState',
  components: {
    Icon
  },
  props: {
    title: {
      type: String,
      required: true
    },
    message: {
      type: String,
      required: true
    },
    actionText: {
      type: String,
      default: 'Criar'
    },
    actionTo: {
      type: String,
      default: null
    }
  }
}
</script>
