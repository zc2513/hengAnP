// 学员相关

import request from '@/utils/request'
import qs from 'qs'

// 获取学员信息
export function getStudents(data) {
    return request({
        url: '/index.php/Master/User/userdiylist',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 查询学员详情
export function userdetails(data) {
    return request({
        url: '/index.php/Master/User/userdetails',
        method: 'post',
        data: qs.stringify(data)
    })
}

