<template>
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <Icon icon="lucide:shield-x" class="w-16 h-16 text-red-500 mx-auto mb-4" />
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Acesso Negado</h2>
            <p class="text-gray-600 mb-6">
                Apenas administradores da empresa podem acessar esta funcionalidade.
            </p>
            <router-link to="/dashboard"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <Icon icon="lucide:arrow-left" class="w-4 h-4 mr-2" />
                Voltar ao Dashboard
            </router-link>
        </div>
    </div>
</template>

<script>
import { Icon } from '@iconify/vue2'

export default {
    name: 'AccessDenied',
    components: {
        Icon
    }
}
</script>
