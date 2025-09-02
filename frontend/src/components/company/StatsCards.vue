<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <Icon icon="lucide:users" class="w-6 h-6 text-white" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total de Usuários</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ totalUsers }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-pink-500 to-red-500 rounded-lg flex items-center justify-center">
                        <Icon icon="lucide:clipboard-list" class="w-6 h-6 text-white" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total de Tarefas</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ totalTasks }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-500 rounded-lg flex items-center justify-center">
                        <Icon icon="lucide:check-circle" class="w-6 h-6 text-white" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Concluídas</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ completedTasks }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center">
                        <Icon icon="lucide:clock" class="w-6 h-6 text-white" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pendentes</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ pendingTasks }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-lg flex items-center justify-center">
                        <Icon icon="lucide:play-circle" class="w-6 h-6 text-white" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Em Andamento</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ inProgressTasks }}</p>
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
        totalUsers: {
            type: Number,
            required: true
        },
        totalTasks: {
            type: Number,
            required: true
        },
        completedTasks: {
            type: Number,
            required: true
        },
        pendingTasks: {
            type: Number,
            required: true
        },
        inProgressTasks: {
            type: Number,
            required: true
        }
    }
}
</script>
