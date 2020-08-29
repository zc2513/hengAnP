import request from '@/utils/request'
import qs from 'qs'


//视频上传
export function index(data) {
        return request({
            url: '/index.php/Master/Upload/index',
            method: 'post',
            data: qs.stringify(data)
        })
    }


    //视频上传
export function upload(data) {
        return request({
            url: '/index.php/Master/Upload/upload',
            method: 'post',
            data: qs.stringify(data)
        })
    }

    //视频转码
export function zm(data) {
        return request({
            url: '/index.php/Master/Upload/zm',
            method: 'post',
            data: qs.stringify(data)
        })
    }


    //获取视频得url已经详情信息
export function url(data) {
        return request({
            url: '/index.php/Master/Upload/url',
            method: 'post',
            data: qs.stringify(data)
        })
    }