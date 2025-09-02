<template>
  <div class="text-center mb-8">
    <div class="mx-auto h-12 w-12 bg-blue-600 rounded-lg flex items-center justify-center mb-4">
      <Icon :icon="icon" class="text-2xl text-white" />
    </div>
    <h2 class="text-2xl font-semibold text-gray-900">{{ title }}</h2>
    <p class="mt-1 text-sm text-gray-600">
      {{ subtitle }}
    </p>
  </div>
</template>

<script>
import { Icon } from '@iconify/vue2'

export default {
  name: 'AuthHeader',
  components: {
    Icon
  },
  props: {
    icon: {
      type: String,
      required: true
    },
    title: {
      type: String,
      required: true
    },
    subtitle: {
      type: String,
      required: true
    }
  }
}
</script>
