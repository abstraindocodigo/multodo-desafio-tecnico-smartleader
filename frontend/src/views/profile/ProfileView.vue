<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Meu Perfil</h1>
                        <p class="mt-1 text-sm text-gray-600">Gerencie suas informações pessoais</p>
                    </div>
                    <router-link to="/dashboard"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <Icon icon="lucide:arrow-left" class="w-4 h-4 mr-2" />
                        Voltar ao Dashboard
                    </router-link>
                </div>
            </div>

            <div v-if="loading" class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-3 text-gray-600">Carregando dados do usuário...</span>
            </div>

            <div v-else-if="user" class="space-y-6">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Informações Pessoais</h2>
                    </div>
                    <div class="px-6 py-4">
                        <div class="flex items-center space-x-6">
                            <div class="flex-shrink-0">
                                <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-2xl font-bold">
                                        {{ user.name?.charAt(0)?.toUpperCase() || 'U' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex-1 min-w-0">
                                <h3 class="text-xl font-semibold text-gray-900">{{ user.name }}</h3>
                                <p class="text-sm text-gray-600">{{ user.email }}</p>
                                <div v-if="user.company" class="mt-2">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <Icon icon="lucide:building" class="w-3 h-3 mr-1" />
                                        {{ user.company.name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Detalhes da Conta</h2>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nome completo</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ user.name || 'Não informado' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ user.email || 'Não informado' }}</dd>
                            </div>
                            <div v-if="user.company">
                                <dt class="text-sm font-medium text-gray-500">Empresa</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ user.company.name }}</dd>
                            </div>
                            <div v-if="user.created_at">
                                <dt class="text-sm font-medium text-gray-500">Membro desde</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ formatDate(user.created_at) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Ações da Conta</h2>
                    </div>
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-end space-x-4">
                            <button @click="refreshUserData" :disabled="refreshing"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <Icon icon="lucide:refresh-cw" class="w-4 h-4 mr-2"
                                    :class="{ 'animate-spin': refreshing }" />
                                {{ refreshing ? 'Atualizando...' : 'Atualizar Dados' }}
                            </button>

                            <button @click="logout"
                                class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <Icon icon="lucide:log-out" class="w-4 h-4 mr-2" />
                                Sair da Conta
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-12">
                <Icon icon="lucide:user-x" class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900 mb-2">Erro ao carregar perfil</h3>
                <p class="text-gray-600 mb-4">Não foi possível carregar os dados do usuário.</p>
                <button @click="loadUserData"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <Icon icon="lucide:refresh-cw" class="w-4 h-4 mr-2" />
                    Tentar Novamente
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { authStore } from '@/stores/authStore'
import { Icon } from '@iconify/vue2'

export default {
    name: 'ProfileView',
    components: {
        Icon
    },
    data() {
        return {
            loading: true,
            refreshing: false
        }
    },
    computed: {
        user() {
            return authStore.user
        }
    },
    async mounted() {
        await this.loadUserData()
    },
    methods: {
        async loadUserData() {
            this.loading = true
            try {
                await authStore.fetchUser()
            } catch (error) {
                console.error('Erro ao carregar dados do usuário:', error)
            } finally {
                this.loading = false
            }
        },
        async refreshUserData() {
            this.refreshing = true
            try {
                await authStore.fetchUser()
            } catch (error) {
                console.error('Erro ao atualizar dados do usuário:', error)
            } finally {
                this.refreshing = false
            }
        },
        logout() {
            authStore.logout()
        },
        formatDate(dateString) {
            if (!dateString) return 'Não informado'
            const date = new Date(dateString)
            return date.toLocaleDateString('pt-BR', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            })
        }
    }
}
</script>

<style scoped></style>
