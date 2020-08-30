import request from '@/utils/request'
import qs from 'qs'

// $where['chapter_id'] = I('chapter_id ');
// $where['video_id'] = I('video_id ');
// $where['title'] = I('title ');
// $where['content'] = I('content ');
// $where['teacher'] = I('teacher ');
// $where['hours'] = I('hours ');
// $where['logo'] = I('logo ');
// $where['url'] = I('url ');
// 新增课程
export function addcourse(data) {
    return request({
        url: '/index.php/Master/Course/addcourse',
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

