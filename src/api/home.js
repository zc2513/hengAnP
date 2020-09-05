import request from '@/utils/request'
import qs from 'qs'

// 查询班级
export function getHome(data) {
    return request({
        url: '/index.php/Master/User/userindex',
        method: 'post',
        data: qs.stringify(data)
    })
}
