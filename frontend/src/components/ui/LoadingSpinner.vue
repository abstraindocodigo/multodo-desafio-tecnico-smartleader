<template>
  <div class="flex items-center justify-center py-12">
    <div class="flex items-center space-x-3">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      <span class="text-gray-600 font-medium">{{ message }}</span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LoadingSpinner',
  props: {
    message: {
      type: String,
      default: 'Carregando...'
    }
  }
}
</script>
