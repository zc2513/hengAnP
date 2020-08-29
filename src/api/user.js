import request from '@/utils/request'
import qs from 'qs'
export function login(data) {
    return request({
        url: '/index.php/Master/Login/login',
        method: 'post',
        data: qs.stringify(data)
    })
}

export function getInfo(token) {
    return request({
        url: '/index.php/Master/Diy/detaildiy',
        method: 'post',
        data: qs.stringify({ id: token })
    })
}

export function logout() {
    return request({
        url: '/index.php/Master/Login/logout',
        method: 'post'
    })
}

export function amendPassword(data) {
    return request({
        url: '/index.php/Master/Diy/detaildiy',
        method: 'post',
        data: qs.stringify(data)
    })
}

