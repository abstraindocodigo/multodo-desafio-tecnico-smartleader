<template>
  <div class="card p-6">
    <div class="flex items-center mb-4 justify-between">
      <div class="w-full flex items-center">
        <Icon icon="lucide:funnel" class="text-gray-500 mr-2" />
        <h4 class="text-lg font-semibold text-gray-900">Filtros</h4>
      </div>
      <div class="flex items-end">
        <button @click="clearFilters" class="btn-secondary w-full flex items-center">
          <Icon icon="lucide:brush-cleaning" class="mr-2 w-5 h-5" />
          <span class="text-sm text-nowrap">Limpar Filtros</span>
        </button>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
        <select v-model="localFilters.status" @change="applyFilters"
          class="w-full p-2 border border-gray-300 rounded-md">
          <option value="">Todos os status</option>
          <option value="pendente">Pendente</option>
          <option value="em_andamento">Em Andamento</option>
          <option value="concluida">Concluída</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Prioridade</label>
        <select v-model="localFilters.priority" @change="applyFilters"
          class="w-full p-2 border border-gray-300 rounded-md">
          <option value="">Todas as prioridades</option>
          <option value="baixa">Baixa</option>
          <option value="media">Média</option>
          <option value="alta">Alta</option>
        </select>
      </div>
    </div>
  </div>
</template>

<script>
import { Icon } from '@iconify/vue2'

export default {
  name: 'TaskFilters',
  components: {
    Icon
  },
  props: {
    filters: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      localFilters: {
        status: '',
        priority: '',
        user_id: ''
      }
    }
  },
  watch: {
    filters: {
      handler(newFilters) {
        this.localFilters = { ...newFilters }
      },
      immediate: true,
      deep: true
    }
  },
  methods: {
    applyFilters() {
      this.$emit('apply-filters', this.localFilters)
    },
    clearFilters() {
      this.localFilters = {
        status: '',
        priority: '',
        user_id: ''
      }
      this.$emit('clear-filters')
    }
  }
}
</script>
