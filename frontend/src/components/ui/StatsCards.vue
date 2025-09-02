<template>
  <div :class="containerClasses">
    <div v-for="(stat, index) in stats" :key="index" :class="cardClasses">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <div :class="iconContainerClasses(stat)">
            <slot :name="`icon-${index}`" :stat="stat" :index="index">
              <Icon :icon="getIconifyIcon(stat)" :class="iconClasses(stat)" />
            </slot>
          </div>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-gray-600">{{ stat.label }}</p>
          <p class="text-xl font-semibold text-gray-900">{{ stat.value }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Icon } from '@iconify/vue2'

export default {
  name: 'StatsCards',
  components: {
    Icon
  },
  props: {
    stats: {
      type: Array,
      default: () => []
    },
    containerClass: {
      type: String,
      default: 'grid grid-cols-1 md:grid-cols-3 gap-4 mb-6'
    },
    cardClass: {
      type: String,
      default: 'bg-white p-4 rounded-lg border border-gray-200'
    },
    theme: {
      type: String,
      default: 'default'
    },
    size: {
      type: String,
      default: 'md'
    }
  },
  computed: {
    containerClasses() {
      return this.containerClass
    },
    cardClasses() {
      return this.cardClass
    },
    defaultIcon() {
      return 'mdi:clipboard-list'
    }
  },
  methods: {
    iconContainerClasses(stat) {
      const sizeClasses = {
        sm: 'w-8 h-8',
        md: 'w-10 h-10',
        lg: 'w-12 h-12'
      }

      const colorClasses = this.getColorClasses(stat.color || 'blue')

      return `${sizeClasses[this.size]} ${colorClasses.bg} rounded-lg flex items-center justify-center`
    },

    iconClasses(stat) {
      const sizeClasses = {
        sm: 'text-sm',
        md: 'text-lg',
        lg: 'text-xl'
      }

      return sizeClasses[this.size]
    },

    getColorClasses(color) {
      const colorMap = {
        blue: { bg: 'bg-blue-100', text: 'text-blue-600' },
        green: { bg: 'bg-green-100', text: 'text-green-600' },
        yellow: { bg: 'bg-yellow-100', text: 'text-yellow-600' },
        red: { bg: 'bg-red-100', text: 'text-red-600' },
        purple: { bg: 'bg-purple-100', text: 'text-purple-600' },
        indigo: { bg: 'bg-indigo-100', text: 'text-indigo-600' },
        pink: { bg: 'bg-pink-100', text: 'text-pink-600' },
        gray: { bg: 'bg-gray-100', text: 'text-gray-600' }
      }

      return colorMap[color] || colorMap.blue
    },

    getIconifyIcon(stat) {
      const iconMap = {
        'Total': 'mdi:clipboard-list',
        'Concluídas': 'mdi:check-circle',
        'Pendentes': 'mdi:clock-outline',
        'Em Andamento': 'mdi:progress-clock',
        'Atrasadas': 'mdi:alert-circle',
        'Prioridade Alta': 'mdi:alert',
        'Prioridade Média': 'mdi:alert-outline',
        'Prioridade Baixa': 'mdi:check-circle-outline'
      }

      return iconMap[stat.label] || 'mdi:chart-box'
    }
  }
}
</script>
