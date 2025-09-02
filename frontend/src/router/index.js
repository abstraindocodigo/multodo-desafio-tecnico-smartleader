import Vue from 'vue'
import VueRouter from 'vue-router'
import { authStore } from '../stores/authStore'

// Importar views
import LoginView from '../views/auth/LoginView.vue'
import RegisterView from '../views/auth/RegisterView.vue'
import ForgotPasswordView from '../views/auth/ForgotPasswordView.vue'
import ResetPasswordView from '../views/auth/ResetPasswordView.vue'
import EmailVerificationView from '../views/auth/EmailVerificationView.vue'
import DashboardView from '../views/dashboard/DashboardView.vue'
import TaskFormView from '../views/tasks/TaskFormView.vue'
import TaskListView from '../views/tasks/TaskListView.vue'
import TaskDetailView from '../views/tasks/TaskDetailView.vue'
import ProfileView from '../views/profile/ProfileView.vue'
import CompanyDashboard from '../views/company/CompanyDashboard.vue'

Vue.use(VueRouter)

// Configurar rotas
const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', component: LoginView, meta: { requiresGuest: true } },
  { path: '/register', component: RegisterView, meta: { requiresGuest: true } },
  { path: '/forgot-password', component: ForgotPasswordView, meta: { requiresGuest: true } },
  { path: '/reset-password', component: ResetPasswordView, meta: { requiresGuest: true } },
  { path: '/email/verify', component: EmailVerificationView, meta: { requiresGuest: true } },
  {
    path: '/dashboard',
    component: DashboardView,
    meta: { requiresAuth: true },
    children: [
      { path: '', component: TaskListView }
    ]
  },
  { path: '/dashboard/task/new', component: TaskFormView, meta: { requiresAuth: true } },
  { path: '/dashboard/task/:id', component: TaskDetailView, props: true, meta: { requiresAuth: true } },
  { path: '/dashboard/task/:id/edit', component: TaskFormView, props: true, meta: { requiresAuth: true } },
  { path: '/company/dashboard', component: CompanyDashboard, meta: { requiresAuth: true, requiresCompanyAdmin: true } },
  { path: '/profile', component: ProfileView, meta: { requiresAuth: true } }
]

const router = new VueRouter({
  mode: 'history',
  routes
})

// Guard de navegação
router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !authStore.isLoggedIn) {
    next('/login')
  } else if (to.meta.requiresGuest && authStore.isLoggedIn) {
    next('/dashboard')
  } else if (to.meta.requiresCompanyAdmin && !authStore.isCompanyAdmin) {
    next('/dashboard')
  } else {
    next()
  }
})

export default router
