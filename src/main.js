import Vue from 'vue'
import 'babel-polyfill'
import 'normalize.css/normalize.css' // CSS resets

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import locale from 'element-ui/lib/locale/lang/zh-CN' // lang i18n

import '@/styles/index.scss' // global css

import { parseTime } from '@/utils/index'
Object.defineProperty(Vue.prototype, '$parseTime', { value: parseTime })

import App from './App'
import store from './store'
import router from './router'

import '@/icons' // icon
import '@/permission' // 权限

import { mockXHR } from '../mock'
if (process.env.NODE_ENV === 'development') {
    mockXHR()
}

Vue.use(ElementUI, { locale })

Vue.config.productionTip = false

new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App)
})
