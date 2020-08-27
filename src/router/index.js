import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@/layout'
import { classManagement, system } from './module/index.js'

/**
 * hidden: true                   true不显示路由,默认为false
 * redirect: noRedirect           如果设置noRedirect路由在面包屑导航中不可被点击
 * name:'router-name'             路由名称，在<keep-alive>中使用(必须设置)
 * alwaysShow:Boolean             默认false不显示,使用的是嵌套模式，也就是子菜单不为单项时显示跟菜单，为单项时显示子菜单
 *                                设置为true时始终显示根菜单
 * meta : {
    roles: ['admin','editor']    控制页面角色权限(可以设置多个角色)
    title: 'title'               侧栏和面包屑展示的名称
    icon: 'svg-name'             图标
    breadcrumb: false            面包屑设置：(默认为true)，如果设置为false，则不再面包屑中显示
    activeMenu: '/example/list'  如果设置路径，侧栏将突出显示您设置的路径
    noCache: true                如果设置为true，页面将不会被缓存(默认false)
    affix: true                   如果设置为true tags-view将固定标签，刷新后保存 (默认为false)
  }
 */

/**
 * path 支持http/s地址
 * constantRoutes
 * 无权限要求的配置数据，所以路由用户均可访问
 */

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
    system,
    // {
    //     path: '/demo',
    //     component: Layout,
    //     redirect: '/demo/table',
    //     name: 'demo',
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
    //         }
    //     ]
    // },
    { path: '*', redirect: '/404', hidden: true }
]

const createRouter = () => new Router({
    // mode: 'history',  // 需要后端开启服务支持
    scrollBehavior: () => ({ y: 0 }),
    routes: constantRoutes
})

const router = createRouter()

export function resetRouter() {
    const newRouter = createRouter()
    router.matcher = newRouter.matcher // 重置路由
}

export default router
