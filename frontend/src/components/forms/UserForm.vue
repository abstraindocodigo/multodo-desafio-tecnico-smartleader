<template>
    <div class="space-y-4">
        <div class="mb-4">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <Icon icon="lucide:user" class="w-5 h-5 mr-2 text-blue-600" />
                Dados Pessoais
            </h3>
            <p class="text-sm text-gray-600">Preencha suas informações pessoais</p>
        </div>

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                Nome completo <span class="text-red-500">*</span>
            </label>
            <input id="name" :value="value.name" @input="updateValue('name', $event.target.value)" type="text" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                :disabled="loading" placeholder="Seu nome completo" />
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                Email <span class="text-red-500">*</span>
            </label>
            <input id="email" :value="value.email" @input="updateValue('email', $event.target.value)" type="email"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                :disabled="loading" placeholder="seu@email.com" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Senha <span class="text-red-500">*</span>
            </label>
            <input id="password" :value="value.password" @input="updateValue('password', $event.target.value)"
                type="password" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                :disabled="loading" placeholder="Mínimo 6 caracteres" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                Confirmar senha <span class="text-red-500">*</span>
            </label>
            <input id="password_confirmation" :value="value.password_confirmation"
                @input="updateValue('password_confirmation', $event.target.value)" type="password" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                :disabled="loading" placeholder="Digite a senha novamente" />
        </div>

        <!-- Company Selection -->
        <div>
            <label for="existing_company_identifier" class="block text-sm font-medium text-gray-700 mb-1">
                Empresa <span class="text-red-500">*</span>
            </label>
            <select id="existing_company_identifier" :value="value.existing_company_identifier"
                @change="updateValue('existing_company_identifier', $event.target.value)" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                :disabled="loading">
                <option value="">Selecione uma empresa...</option>
                <option v-for="company in companies" :key="company.id" :value="company.identifier">
                    {{ company.name }} ({{ company.identifier }})
                </option>
            </select>
            <p class="mt-1 text-xs text-gray-500">Escolha a empresa à qual deseja se associar.</p>
        </div>


    </div>
</template>

<script>
import { Icon } from '@iconify/vue2'

export default {
    name: 'UserForm',
    props: {
        value: {
            type: Object,
            required: true
        },
        loading: {
            type: Boolean,
            default: false
        },
        companies: {
            type: Array,
            default: () => []
        }
    },
    components: {
        Icon
    },
    methods: {
        updateValue(field, newValue) {
            this.$emit('input', {
                ...this.value,
                [field]: newValue
            })
        }
    }
}
</script>
