<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Access Denied Message -->
        <AccessDenied v-if="!authStore.isCompanyAdmin" />

        <!-- Dashboard Content -->
        <div v-else>
            <div class="max-w-6xl mx-auto px-6 lg:px-8 py-8">
                <!-- Stats Cards -->
                <StatsCards :total-users="companyStore.totalUsers" :total-tasks="companyStore.totalTasks"
                    :completed-tasks="companyStore.completedTasks" :pending-tasks="companyStore.pendingTasks"
                    :in-progress-tasks="companyStore.inProgressTasks" />

                <!-- Filters -->
                <CompanyFilters :filters="filters" :company-users="companyStore.companyUsers"
                    @update:filters="updateFilters" />

                <!-- Main Content -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Users Section -->
                    <UsersSection :filtered-users="filteredUsers" :selected-user="selectedUser"
                        :loading="companyStore.loading" @select-user="selectUser" @refresh="refreshData" />

                    <!-- User Tasks Section -->
                    <UserTasks :selected-user="selectedUser" @close="selectedUser = null" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { companyStore } from '@/stores/companyStore'
import { authStore } from '@/stores/authStore'
import AccessDenied from '@/components/company/AccessDenied.vue'
import StatsCards from '@/components/company/StatsCards.vue'
import CompanyFilters from '@/components/company/CompanyFilters.vue'
import UsersSection from '@/components/company/UsersSection.vue'
import UserTasks from '@/components/company/UserTasks.vue'

export default {
    name: 'CompanyDashboard',
    components: {
        AccessDenied,
        StatsCards,
        CompanyFilters,
        UsersSection,
        UserTasks
    },
    data() {
        return {
            companyStore,
            authStore,
            selectedUser: null,
            filters: {
                user_id: '',
                status: '',
                priority: ''
            }
        }
    },
    computed: {
        filteredUsers() {
            let users = this.companyStore.companyUsers

            if (this.filters.user_id) {
                users = users.filter(user => user.id == this.filters.user_id)
            }

            return users
        }
    },
    async mounted() {
        await this.loadData()
    },
    methods: {
        async loadData() {
            await Promise.all([
                this.companyStore.loadCompanyUsers(),
                this.companyStore.loadAllUsersTasks()
            ])
        },

        async refreshData() {
            await this.loadData()
        },

        selectUser(user) {
            this.selectedUser = user
        },

        getUserTasks(userId) {
            return this.companyStore.getUserTasks(userId)
        },

        getUserTaskStats(userId) {
            return this.companyStore.getUserTaskStats(userId)
        },

        updateFilters(newFilters) {
            this.filters = newFilters
            this.companyStore.setFilters(newFilters)
        },

        applyFilters() {
            this.companyStore.setFilters(this.filters)
        },

        clearFilters() {
            this.filters = {
                user_id: '',
                status: '',
                priority: ''
            }
            this.companyStore.clearFilters()
        }
    }
}
</script>
