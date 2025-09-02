<template>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <AuthHeader icon="mdi:email-check" title="Verificação de Email"
                subtitle="Verificando seu endereço de email..." />

            <div class="bg-white py-8 px-6 shadow-sm border border-gray-200 rounded-lg">
                <div v-if="loading" class="text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                    <p class="text-gray-600">Verificando seu email...</p>
                </div>

                <div v-else-if="success" class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <Icon icon="mdi:check" class="w-8 h-8 text-green-600" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Email Verificado!</h3>
                    <p class="text-gray-600 mb-6">{{ message }}</p>
                    <router-link to="/login"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                        Fazer Login
                    </router-link>
                </div>

                <div v-else-if="error" class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <Icon icon="mdi:alert-circle" class="w-8 h-8 text-red-600" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Erro na Verificação</h3>
                    <p class="text-gray-600 mb-6">{{ error }}</p>
                    <div class="space-y-3">
                        <router-link to="/login"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                            Ir para Login
                        </router-link>
                        <div>
                            <button @click="resendVerification"
                                class="text-sm text-blue-600 hover:text-blue-500 transition-colors duration-150">
                                Reenviar email de verificação
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AuthHeader from '@/components/ui/AuthHeader.vue'
import { Icon } from '@iconify/vue2'
import { authStore } from '@/stores/authStore'

export default {
    name: 'EmailVerificationView',
    components: {
        AuthHeader,
        Icon
    },
    data() {
        return {
            loading: true,
            success: false,
            error: '',
            message: ''
        }
    },
    async mounted() {
        await this.verifyEmail()
    },
    methods: {
        async verifyEmail() {
            try {
                this.loading = true

                // Verificar se temos uma URL de verificação na query string
                const verificationUrl = this.$route.query.url

                if (!verificationUrl) {
                    this.error = 'Link de verificação inválido ou expirado'
                    this.loading = false
                    return
                }

                // Decodificar a URL
                const decodedUrl = decodeURIComponent(verificationUrl)

                // Extrair o path da URL para fazer a requisição
                const url = new URL(decodedUrl)
                const apiPath = url.pathname + url.search

                const result = await authStore.verifyEmail(apiPath)

                if (result.success) {
                    this.success = true
                    this.message = result.message || 'Email verificado com sucesso! Agora você pode fazer login.'

                    setTimeout(() => {
                        this.$router.push({
                            path: '/login',
                            query: { message: this.message }
                        })
                    }, 2000)
                } else {
                    this.error = result.error || 'Erro ao verificar email'
                }
            } catch (error) {
                console.error('Erro na verificação:', error)
                this.error = error.message || 'Erro inesperado ao verificar email'
            } finally {
                this.loading = false
            }
        },

        async resendVerification() {
            try {
                this.$router.push('/register')
            } catch (error) {
                console.error('Erro ao reenviar verificação:', error)
            }
        }
    }
}
</script>

<style scoped>
.bg-white {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
