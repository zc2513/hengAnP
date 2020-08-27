/**
 * @description 系统管理
 */

import Layout from '@/layout'

const system = {
    path: '/system',
    component: Layout,
    redirect: '/system/amendPasspord',
    alwaysShow: true,
    meta: { title: '系统管理', icon: 'system' },
    children: [
        {
            path: 'amendPasspord',
            name: 'amendPasspord',
            component: () => import('@/views/system/amendPasspord/index'),
            meta: { title: '修改密码', icon: 'amendPasspord' }
        }
    ]
}

export default system
