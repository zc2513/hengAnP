<?php
namespace Master\Controller;
use Think\Controller;
vendor("voduploadsdk.index");
header('Access-Control-Allow-Origin:*'); // *代表允许任何网址请求
header("Content-type: text/html; charset=utf-8");
class CourseController extends Controller{


    /*
         * 生成唯一的班级编号
         */
    public function order_number(){
        return date('ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }


    public function addcourse(){
        $where['chapter_id'] = I('chapter_id');
        $where['manager_id'] = I('manager_id');
        $where['diy_id'] = I('diy_id');
        $where['video_id'] = I('video_id');
        $where['title'] = I('title');
        $where['content'] = I('content');
        $where['teacher'] = I('teacher');
        $where['hours'] = I('hours');
        $where['logo'] = I('logo');
        $where['typesStr'] = I('typesStr');
        if(!empty($this->url(I('video_id')))){
            $where['url'] = $this->url(I('video_id'));
             M("course") -> add($where);
            $this -> ajaxReturn(array('msg' => '新增成功','code' => 200));
        }
//        $res?$this -> ajaxReturn(array('msg' => '新增成功','code' => 200)):$this->ajaxReturn(array('msg' => '新增失败','code' => 200));
     }

    //获取视频得url
    public function url($VideoId){
//        $VideoId = 'cda22bed60ed4704a200916106f91e10';
        try {
            $Apivod = new \ApiVodUpload();
            $client = $Apivod -> initVodClient("LTAI4G8ik45GSfhvLjo8HPdB", "dzx7zmOud6gPfZswZxKUwtBVwvhJwz");
            $playInfo =  $Apivod-> getPlayInfo($client, $VideoId);
            return $playInfo->PlayInfoList->PlayInfo[0]->PlayURL;
        } catch (Exception $e) {
            print $e->getMessage()."\n";
        }

    }


    public function updatecourse(){
        $where['id'] = I('id');
        $data['chapter_id'] = I('chapter_id');
        $data['video_id'] = I('video_id');
        $data['title'] = I('title');
        $data['content'] = I('content');
        $data['teacher'] = I('teacher');
        $data['hours'] = I('hours');
        $data['logo'] = I('logo');
        $data['url'] = I('url');
        $res = M('course') -> where($where) -> save(array_filter($data));
//        echo M()->getLastSql();
        $res?$this -> ajaxReturn(array('msg' => '修改成功','code' => 200)):$this->ajaxReturn(array('msg' => '修改失败','code' => 200));
    }

    public function delectecourse(){
        $where['id'] = I('id');
        $res = M('course') -> where($where) -> delete();
        $res?$this -> ajaxReturn(array('msg' => '删除成功','code' => 200)):$this->ajaxReturn(array('msg' => '删除失败','code' => 200));
    }


    public function page($d,$where='',$pageNum = 1, $pageSize = 5,$order = "id DESC")
    {
        $res = M($d) ->  where($where) ->limit(($pageNum - 1) * $pageSize,$pageSize)-> order($order) ->select();
        return $res;
    }

    public function count($d,$where=''){
        $count = M($d) ->  where($where)-> count();
        return $count;
    }

    public function selectcourse(){
        $pageNum = I('page');
//        $pageNum = 1;
        $pageSize = I('size');
//        $pageSize = 2;
        $where['manager_id'] = I('manager_id');
//        $where['manager_id'] = 2;
        $where['title'] = I('title');
        $where['teacher'] = I('teacher');
        $res = $this->page('course',array_filter($where),$pageNum,$pageSize);
        $count = $this->count('course',array_filter($where));
        $data['total '] = $count;
        $data['list'] = $res;
        $res?$this -> ajaxReturn(array('data' => $data,'code' => 200)):$this->ajaxReturn(array('data' => '','code' => 200));
    }

    public function detailscourse(){
        $where['id'] = I('id');
        $res = M('course') -> where($where) -> find();
        $res?$this -> ajaxReturn(array('data' => $res,'code' => 200)):$this->ajaxReturn(array('data' => '','code' => 200));

    }


    public function courseevaluate(){
        $res =  M('course_comment') -> select();
        $res?$this -> ajaxReturn(array('data' => $res,'code' => 200)):$this->ajaxReturn(array('data' => '','code' => 200));
    }




}
