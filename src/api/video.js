import request from '@/utils/request'
import qs from 'qs'

// 视频上传
export function index(data) {
    return request({
        url: '/index.php/Master/Upload/index',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 视频上传
export function upload(data) {
    return request({
        url: '/index.php/Master/Upload/upload',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 视频转码
export function zm(data) {
    return request({
        url: '/index.php/Master/Upload/zm',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 获取视频得url已经详情信息
export function url(data) {
    return request({
        url: '/index.php/Master/Upload/url',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 获取阿里云凭证
// http://w.safetymf.com/index.php/Wechat/Upload/index?Videoname=测试数据啊&CoverURL=https://dss3.bdstatic.com/70cFv8Sh_Q1YnxGkpoWK1HF6hhy/it/u=2534506313,1688529724&fm=26&gp=0.jpg&Description=我是描述信息
export function getTicket() {
    return request({
        url: 'http://demo-vod.cn-shanghai.aliyuncs.com/voddemo/CreateSecurityToken?BusinessType=vodai&TerminalType=pc&DeviceModel=iPhone9,2&UUID=67999yyuuuy&AppVersion=1.0.0',
        method: 'get'
    })
}

// 视频转码
export function videoTranscoding(videoId) {
    return request({
        url: '/index.php?m=Wechat&c=Upload&a=zm',
        method: 'get',
        params: { videoId }
    })
}

// 获取上传后视频地址
export function getVideoUrl(videoId) {
    return request({
        url: '/index.php/Wechat/Upload/url',
        method: 'get',
        params: { videoId }
    })
}

/**
 * @description 视频上传
 * @author zc2513
 * @date 2020-09-13
 * @export
 * @param {*} data
 * @returns
 */
export function upfileVideoApi(params) {
    return request({
        url: '/index.php/Wechat/Upload/index',
        method: 'get',
        params: qs.stringify(params)
    })
}
