import request from '@/utils/request'

import qs from 'qs'

// 查询班级
export function selectclass(data) {
    return request({
        url: '/index.php/Master/Class/selectclass',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 获取章列表
export function chapterlist(data) {
    return request({
        url: '/index.php/Master/Chapter/addchapterlist',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 获取节列表
export function getKnotList(data) {
    return request({
        url: '/index.php/Master/Course/selectcourse',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 新增班级
export function addclass(data) {
    return request({
        url: '/index.php/Master/Class/addclass',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 获取班级详情修改
export function detailsclass(data) {
    return request({
        url: '/index.php/Master/Class/detailsclass',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 更新新班级信息-结业
export function updateclass(data) {
    return request({
        url: '/index.php/Master/Class/updateclass',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 获取班级学员信息
export function getClasslist(data) {
    return request({
        url: '/index.php/Master/User/classlist',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 删除班级学员
export function delperson(data) {
    return request({
        url: '/index.php/Master/User/delperson',
        method: 'post',
        data: qs.stringify(data)
    })
}

// classlist
// 删除/解散班级
export function delclass(data) {
    return request({
        url: '/index.php/Master/Class/delclass',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 二维码
// http://w.safetymf.com/index.php/Master/Qrcode/index?id=12&classid=39

