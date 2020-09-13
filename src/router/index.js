import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@/layout'
import { classManagement, system } from './module/index.js'
export const constantRoutes = [
    {
        path: '/redirect',
        component: Layout,
        hidden: true,
        children: [
            {
                path: '/redirect/:path*',
                component: () => import('@/views/redirect/index')
            }
        ]
    },
    {
        path: '/login',
        component: () => import('@/views/login/index'),
        hidden: true
    },
    {
        path: '/404',
        component: () => import('@/views/404'),
        hidden: true
    },
    {
        path: '/',
        component: Layout,
        redirect: '/home',
        children: [{
            path: 'home',
            name: 'Home',
            component: () => import('@/views/home/index'),
            meta: { title: '机构总览', icon: 'home', affix: true }
        }]
    },
    classManagement,
    {
        path: '/curriculum',
        component: Layout,
        children: [{
            path: '',
            name: 'curriculum',
            component: () => import('@/views/curriculum/index'),
            meta: { title: '课程管理', icon: 'curriculum' }
        }]
    },
    // {
    //     path: '/recommend',
    //     component: Layout,
    //     children: [{
    //         path: '',
    //         name: 'recommend',
    //         component: () => import('@/views/recommend/index'),
    //         meta: { title: '推荐课程', icon: 'curriculum' }
    //     }]
    // },
    {
        path: '/student',
        component: Layout,
        children: [{
            path: '',
            name: 'student',
            component: () => import('@/views/student/index'),
            meta: { title: '学员管理', icon: 'student' }
        }]
    },
    {
        path: '/enroll',
        component: Layout,
        children: [{
            path: '',
            name: 'enroll',
            component: () => import('@/views/enroll/index'),
            meta: { title: '报名列表', icon: 'enroll' }
        }]
    },
    system,
    // {
    //     path: '/demo',
    //     component: Layout,
    //     redirect: '/demo/table',
    //     name: 'demo',
    //     // hidden: true,
    //     meta: { title: 'demo', icon: 'example' },
    //     children: [
    //         {
    //             path: 'table',
    //             name: 'Table',
    //             component: () => import('@/views/demo/table/index'),
    //             meta: { title: 'Table', icon: 'table' }
    //         },
    //         {
    //             path: 'form',
    //             name: 'Form',
    //             component: () => import('@/views/demo/form/index'),
    //             meta: { title: 'form', icon: 'eye', noCache: true }
    //         },
    //         {
    //             path: 'rich',
    //             name: 'rich',
    //             component: () => import('@/views/demo/rich/index'),
    //             meta: { title: '富文本', icon: 'nested' }
    //         },
    //         {
    //             path: 'tree',
    //             name: 'Tree',
    //             component: () => import('@/views/demo/tree/index'),
    //             meta: { title: 'Tree', icon: 'tree' }
    //         },
    //         {
    //             path: '/upfile',
    //             name: 'upfile',
    //             component: () => import('@/views/demo/upfile/index'),
    //             meta: { title: 'upfile', icon: 'table' }
    //         },
    //         {
    //             path: '/video',
    //             name: 'video',
    //             component: () => import('@/views/demo/video/index'),
    //             meta: { title: 'video', icon: 'table' }
    //         }
    //     ]
    // },
    { path: '*', redirect: '/404', hidden: true },
    { path: '/dayin', component: () => import('@/views/dayin'), hidden: true }
]

const createRouter = () => new Router({
    // mode: 'history',  // 需要后端开启服务支持
    scrollBehavior: () => ({ y: 0 }),
    routes: constantRoutes
})

export const asyncRoutes = []

const router = createRouter()

export function resetRouter() {
    const newRouter = createRouter()
    router.matcher = newRouter.matcher // 重置路由
}

export default router
