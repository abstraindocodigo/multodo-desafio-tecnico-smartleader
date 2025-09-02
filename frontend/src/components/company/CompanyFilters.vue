<template>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="userFilter" class="block text-sm font-medium text-gray-700 mb-2">Buscar por
                    usuário:</label>
                <select id="userFilter" :value="filters.user_id" @change="updateFilters"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Todos os usuários</option>
                    <option v-for="user in companyUsers" :key="user.id" :value="user.id">
                        {{ user.name }}
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CompanyFilters',
    props: {
        filters: {
            type: Object,
            required: true
        },
        companyUsers: {
            type: Array,
            required: true
        }
    },
    methods: {
        updateFilters(event) {
            const newFilters = {
                ...this.filters,
                user_id: event.target.value
            }
            this.$emit('update:filters', newFilters)
        }
    }
}
</script>
