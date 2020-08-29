import request from '@/utils/request'
import qs from 'qs'

//图片上传
export function imgUpload(data) {
        return request({
            url: '/index.php/Wechat/ImgUpload/imgUpload',
            method: 'post',
            data: qs.stringify(data)
        })
    }