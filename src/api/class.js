import request from '@/utils/request'
import qs from 'qs'
// $pageNum = I('pageNum ');
// $pageSize = I('pageSize');
// $data['id'] = I('id');                  修改需要传id
// $where['classid'] = $this -> order_number();   //班级id
// $where['manager_id'] =I('manager_id');          机构id
// $where['classname'] = I('classname');     班级名称
// $where['status'] = I('status');          班级状态   0 招生   1 开班   2  结业
// $where['addtime'] = I('addtime');         创建时间
// $where['classnum'] = I('classnum');       班级人数
// $where['teacher'] =I('teacher');         代课老师
// $where['way'] = I('way');                报名方式
// $where['startclass'] = I('startclass');     开始时间
// $where['endclass'] = I('endclass');          结束时间
// $where['course'] = I('course');           课程
// $where['classhour'] = I('classhour');     课时时长
// $where['type1_id'] = I('type1_id');       type1-6
// $where['type2_id'] = I('type2_id');
// $where['type3_id'] = I('type3_id');
// $where['type4_id'] = I('type4_id');
// $where['type5_id'] = I('type5_id');
// $where['type6_id'] = I('type6_id');

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

// 更新新班级信息
export function updateclass(data) {
    return request({
        url: '/index.php/Master/Class/updateclass',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 删除班级
export function delclass(data) {
    return request({
        url: '/index.php/Master/Class/delclass',
        method: 'post',
        data: qs.stringify(data)
    })
}

// 二维码
// http://w.safetymf.com/index.php/Master/Qrcode/index?id=12&classid=39

