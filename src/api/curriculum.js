import request from '@/utils/request'
import qs from 'qs'

// 新增/编辑课程
export function addcourse(data, url) {
    return request({
        url: `/index.php/Master/Course/${url}`,
        method: 'post',
        data: qs.stringify(data)
    })
}

// 删除课程
export function delectecourse(data) {
    return request({
        url: '/index.php/Master/Course/delectecourse',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 查询课程
export function selectcourse(data) {
    return request({
        url: '/index.php/Master/Course/selectcourse',
        method: 'post',
        data: qs.stringify(data)
    })
}
// 课程详情
export function detailscourse(data) {
    return request({
        url: '/index.php/Master/Course/detailscourse',
        method: 'post',
        data: qs.stringify(data)
    })
}

