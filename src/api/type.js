import request from '@/utils/request'
import qs from 'qs'

// 1级
export function type1(data) {
    return request({
        url: '/index.php/Master/Type/type1',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 2级
export function type2(data) {
    return request({
        url: '/index.php/Master/Type/type2',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 3级
export function type3(data) {
    return request({
        url: '/index.php/Master/Type/type3',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 4级
export function type4(data) {
    return request({
        url: '/index.php/Master/Type/type4',
        method: 'post',
        data: qs.stringify(data)
    })
}
