<template>
  <div v-if="loading" class="global-loader">
    <div class="loader-overlay">
      <div class="loader-content">
        <Icon icon="mdi:loading" class="iconify-spinner" />
        <p class="loader-text">{{ message }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import { Icon } from '@iconify/vue2'

export default {
  name: 'GlobalLoader',
  components: {
    Icon
  },
  props: {
    loading: {
      type: Boolean,
      default: false
    },
    message: {
      type: String,
      default: 'Carregando...'
    }
  }
}
</script>

<style scoped>
.global-loader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  animation: fadeIn 0.2s ease-out;
}

.loader-overlay {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(4px);
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.loader-content {
  text-align: center;
  padding: 2rem;
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(0, 0, 0, 0.05);
}

.iconify-spinner {
  font-size: 3rem;
  color: #3b82f6;
  margin: 0 auto 1rem;
  display: block;
  animation: icon-spin 1s linear infinite;
}

.loader-text {
  color: #6b7280;
  font-size: 0.875rem;
  font-weight: 500;
  margin: 0;
}

@keyframes icon-spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

/* Responsividade */
@media (max-width: 640px) {
  .loader-content {
    padding: 1.5rem;
    margin: 1rem;
  }

  .iconify-spinner {
    font-size: 2.2rem;
  }

  .loader-text {
    font-size: 0.8rem;
  }
}
</style>
