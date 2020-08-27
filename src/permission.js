import router from './router'
import store from './store'
import { Message } from 'element-ui'
import NProgress from 'nprogress' // 进度条
import 'nprogress/nprogress.css' // 进度条样式
import { getToken } from '@/utils/auth'
import getPageTitle from '@/utils/get-page-title'

NProgress.configure({ showSpinner: false }) // 进度条配置 是否开启圆滚滚

const whiteList = ['/login'] // 白名单

router.beforeEach(async(to, from, next) => {
    NProgress.start()
    // 页面标题设置
    document.title = getPageTitle(to.meta.title)

    const hasToken = getToken()

    if (hasToken) {
        if (to.path === '/login') {
            next({ path: '/' })
            NProgress.done()
        } else {
            // 1.1 获取用户信 成功下一页失败则从写获取
            const hasGetUserInfo = store.getters.name
            if (hasGetUserInfo) {
                next()
            } else {
                try { // 根据token尝试获取 获取到下一页
                    await store.dispatch('user/getInfo')
                    next()
                } catch (error) { // 获取不到删除token并重新登录
                    await store.dispatch('user/resetToken')
                    Message.error(error || 'Has Error')
                    next(`/login?redirect=${to.path}`)
                    NProgress.done()
                }
            }
        }
    } else { // 未登录处理
        if (whiteList.indexOf(to.path) !== -1) { // 白名单
            next()
        } else { // 无权访问，跳转页面
            next(`/login?redirect=${to.path}`)
            NProgress.done()
        }
    }
})

router.afterEach(() => {
    NProgress.done()
})

