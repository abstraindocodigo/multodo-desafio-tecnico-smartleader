<template>
    <div v-if="selectedUser" class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <Icon icon="lucide:clipboard-list" class="w-5 h-5 mr-2 text-blue-600" />
                    Tarefas de {{ selectedUser.name }}
                </h3>
                <button @click="$emit('close')"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <Icon icon="lucide:x" class="w-4 h-4 mr-2" />
                    Fechar
                </button>
            </div>
        </div>

        <div class="p-6">
            <!-- User Task Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">Total</p>
                    <p class="text-lg font-semibold text-gray-900">{{
                        getUserTaskStats(selectedUser.id).total }}</p>
                </div>
                <div class="text-center p-3 bg-green-50 rounded-lg">
                    <p class="text-xs text-green-600 mb-1">Concluídas</p>
                    <p class="text-lg font-semibold text-green-700">{{
                        getUserTaskStats(selectedUser.id).completed }}</p>
                </div>
                <div class="text-center p-3 bg-yellow-50 rounded-lg">
                    <p class="text-xs text-yellow-600 mb-1">Pendentes</p>
                    <p class="text-lg font-semibold text-yellow-700">{{
                        getUserTaskStats(selectedUser.id).pending }}</p>
                </div>
                <div class="text-center p-3 bg-blue-50 rounded-lg">
                    <p class="text-xs text-blue-600 mb-1">Em Andamento</p>
                    <p class="text-lg font-semibold text-blue-700">{{
                        getUserTaskStats(selectedUser.id).inProgress }}</p>
                </div>
            </div>

            <!-- Tasks List -->
            <div class="space-y-4">
                <div v-for="task in getUserTasks(selectedUser.id)" :key="task.id"
                    class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow"
                    :class="{
                        'border-l-4 border-l-green-500': task.status === 'concluida',
                        'border-l-4 border-l-blue-500': task.status === 'em_andamento',
                        'border-l-4 border-l-yellow-500': task.status === 'pendente'
                    }">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="text-sm font-medium text-gray-900">{{ task.title }}</h4>
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                            :class="{
                                'bg-green-100 text-green-800': task.priority === 'baixa',
                                'bg-yellow-100 text-yellow-800': task.priority === 'media',
                                'bg-red-100 text-red-800': task.priority === 'alta'
                            }">
                            {{ getPriorityLabel(task.priority) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">{{ task.description }}</p>
                    <div class="flex justify-between items-center text-xs">
                        <span class="inline-flex items-center px-2 py-1 rounded-full font-medium"
                            :class="{
                                'bg-green-100 text-green-800': task.status === 'concluida',
                                'bg-blue-100 text-blue-800': task.status === 'em_andamento',
                                'bg-yellow-100 text-yellow-800': task.status === 'pendente'
                            }">
                            {{ getStatusLabel(task.status) }}
                        </span>
                        <span class="text-gray-500 flex items-center">
                            <Icon icon="lucide:calendar" class="w-3 h-3 mr-1" />
                            {{ formatDate(task.created_at) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Icon } from '@iconify/vue2'

export default {
    name: 'UserTasks',
    components: {
        Icon
    },
    props: {
        selectedUser: {
            type: Object,
            default: null
        }
    },
    methods: {
        getUserTasks(userId) {
            return this.$parent.getUserTasks(userId)
        },
        getUserTaskStats(userId) {
            return this.$parent.getUserTaskStats(userId)
        },
        getStatusLabel(status) {
            const labels = {
                'pendente': 'Pendente',
                'em_andamento': 'Em Andamento',
                'concluida': 'Concluída'
            }
            return labels[status] || status
        },
        getPriorityLabel(priority) {
            const labels = {
                'baixa': 'Baixa',
                'media': 'Média',
                'alta': 'Alta'
            }
            return labels[priority] || priority
        },
        formatDate(dateString) {
            const date = new Date(dateString)
            return date.toLocaleDateString('pt-BR')
        }
    }
}
</script>
