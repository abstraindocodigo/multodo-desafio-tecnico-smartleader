<template>
    <div class="flex items-center justify-between px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
        <!-- Info de registros -->
        <div class="flex-1 flex justify-between sm:hidden">
            <button @click="$emit('prev-page')" :disabled="!hasPrevPage"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                Anterior
            </button>
            <button @click="$emit('next-page')" :disabled="!hasNextPage"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                Próximo
            </button>
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Mostrando
                    <span class="font-medium">{{ from }}</span>
                    até
                    <span class="font-medium">{{ to }}</span>
                    de
                    <span class="font-medium">{{ total }}</span>
                    resultados
                </p>
            </div>

            <!-- Navegação -->
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <!-- Botão Anterior -->
                    <button @click="$emit('prev-page')" :disabled="!hasPrevPage"
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        <Icon icon="mdi:chevron-left" class="h-5 w-5" />
                    </button>

                    <!-- Páginas -->
                    <template v-for="page in visiblePages">
                        <button v-if="page !== '...'" :key="`page-${page}`" @click="$emit('go-to-page', page)" :class="[
                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                            page === currentPage
                                ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                        ]">
                            {{ page }}
                        </button>
                        <span v-else :key="`ellipsis-${page}`"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                            ...
                        </span>
                    </template>

                    <!-- Botão Próximo -->
                    <button @click="$emit('next-page')" :disabled="!hasNextPage"
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        <Icon icon="mdi:chevron-right" class="h-5 w-5" />
                    </button>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
import { Icon } from '@iconify/vue2'

export default {
    name: 'PaginationComponent',
    components: {
        Icon
    },
    props: {
        currentPage: {
            type: Number,
            required: true
        },
        totalPages: {
            type: Number,
            required: true
        },
        total: {
            type: Number,
            required: true
        },
        from: {
            type: Number,
            required: true
        },
        to: {
            type: Number,
            required: true
        }
    },
    computed: {
        hasPrevPage() {
            return this.currentPage > 1
        },
        hasNextPage() {
            return this.currentPage < this.totalPages
        },
        visiblePages() {
            const pages = []
            const current = this.currentPage
            const total = this.totalPages

            if (total <= 7) {
                // Se tem 7 páginas ou menos, mostra todas
                for (let i = 1; i <= total; i++) {
                    pages.push(i)
                }
            } else {
                // Lógica para mostrar páginas com ellipsis
                if (current <= 4) {
                    // Páginas iniciais
                    for (let i = 1; i <= 5; i++) {
                        pages.push(i)
                    }
                    pages.push('...')
                    pages.push(total)
                } else if (current >= total - 3) {
                    // Páginas finais
                    pages.push(1)
                    pages.push('...')
                    for (let i = total - 4; i <= total; i++) {
                        pages.push(i)
                    }
                } else {
                    // Páginas do meio
                    pages.push(1)
                    pages.push('...')
                    for (let i = current - 1; i <= current + 1; i++) {
                        pages.push(i)
                    }
                    pages.push('...')
                    pages.push(total)
                }
            }

            return pages
        }
    }
}
</script>
