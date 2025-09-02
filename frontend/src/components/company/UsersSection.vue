<template>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <Icon icon="lucide:users" class="w-5 h-5 mr-2 text-blue-600" />
                    Usuários da Empresa
                </h3>
                <button @click="$emit('refresh')"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                    :disabled="loading">
                    <Icon icon="lucide:refresh-cw" class="w-4 h-4 mr-2" :class="{ 'animate-spin': loading }" />
                    Atualizar
                </button>
            </div>
        </div>

        <div class="p-6">
            <div v-if="loading" class="text-center py-12">
                <Icon icon="lucide:loader-2" class="w-8 h-8 text-gray-400 animate-spin mx-auto mb-4" />
                <p class="text-gray-500">Carregando dados...</p>
            </div>

            <div v-else-if="filteredUsers.length === 0" class="text-center py-12">
                <Icon icon="lucide:users" class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                <p class="text-gray-500">Nenhum usuário encontrado</p>
            </div>

            <div v-else class="space-y-4">
                <div v-for="user in filteredUsers" :key="user.id" @click="$emit('select-user', user)"
                    class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                    :class="{ 'ring-2 ring-blue-500 bg-blue-50': selectedUser?.id === user.id }">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <Icon icon="lucide:user" class="w-6 h-6 text-white" />
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h4 class="text-sm font-medium text-gray-900">{{ user.name }}</h4>
                        <p class="text-sm text-gray-500">{{ user.email }}</p>
                        <div class="flex gap-2 mt-2">
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <Icon icon="lucide:clipboard-list" class="w-3 h-3 mr-1" />
                                {{ getUserTaskStats(user.id).total }}
                            </span>
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <Icon icon="lucide:check" class="w-3 h-3 mr-1" />
                                {{ getUserTaskStats(user.id).completed }}
                            </span>
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <Icon icon="lucide:clock" class="w-3 h-3 mr-1" />
                                {{ getUserTaskStats(user.id).pending }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Icon } from '@iconify/vue2'

export default {
    name: 'UsersSection',
    components: {
        Icon
    },
    props: {
        filteredUsers: {
            type: Array,
            required: true
        },
        selectedUser: {
            type: Object,
            default: null
        },
        loading: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        getUserTaskStats(userId) {
            return this.$parent.getUserTaskStats(userId)
        }
    }
}
</script>
