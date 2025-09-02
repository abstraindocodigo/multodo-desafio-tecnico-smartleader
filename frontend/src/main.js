import Vue from 'vue'
import App from './App.vue'
import router from './router'
import { authStore } from './stores/authStore'
import './assets/css/main.css'

Vue.config.productionTip = false

new Vue({
  router,
  render: h => h(App),
  created () {
    authStore.initializeAuth()
  }
}).$mount('#app')
