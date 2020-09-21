<?php
namespace Admin\Controller;
require_once './aliyun-php-sdk/aliyun-php-sdk-core/Config.php';   // 假定您的源码文件和aliyun-php-sdk处于同一目录
use vod\Request\V20170321 as vod;

use \Admin\Controller\BaseController;

class CourseController extends BaseController{
    //章节列表
    public function chapter_lst(){
        $model=D('chapter');
        $course_model=D('course');
        $type1=D('type1')->select();
        if(!empty(I('get.'))){
            if(!empty(I('get.title'))){
                $where['title']=array('like','%'.I('get.title').'%');
            }
            if(!empty(I('get.type1Id'))){
                $where['type1_id']=array('eq',I('get.type1Id'));
            }
            if(!empty(I('get.type2Id'))){
                $where['type1_id']=array('eq',I('get.type2Id'));
            }
            if(!empty(I('get.type3Id'))){
                $where['type1_id']=array('eq',I('get.type3Id'));
            }
            if(!empty(I('get.type4Id'))){
                $where['type1_id']=array('eq',I('get.type4Id'));
            }
            $count=$model->where($where)->count();
            $page = new \Think\Page($count,5);
            $page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
            $page->setConfig('prev', '上一页');
            $page->setConfig('next', '下一页');
            $page->setConfig('last', '末页');
            $page->setConfig('first', '首页');
            $page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
            $page_show = $page->show();
            $this->assign('page',$page_show);

            $data=$model->where($where)->limit($page->firstRow,$page->listRows)->select();
        }else{
            $count=$model->count();
            $page = new \Think\Page($count,5);
            $page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
            $page->setConfig('prev', '上一页');
            $page->setConfig('next', '下一页');
            $page->setConfig('last', '末页');
            $page->setConfig('first', '首页');
            $page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
            $page_show = $page->show();
            $this->assign('page',$page_show);
            $data=$model->limit($page->firstRow,$page->listRows)->select();
        }
        foreach ($data as $key=>$value){
            if($value['is_free']){
                $data[$key]['is_free']="免费";
            }else{
                $data[$key]['is_free']="收费";
            }
            $type1_name=D('type1')->find($value['type1_id'])['type1_name'];
            $type2_name=D('type2')->find($value['type2_id'])['type2_name'];
            $type3_name=D('type3')->find($value['type3_id'])['type3_name'];
            $type4_name=D('type4')->find($value['type4_id'])['type4_name'];
            $data[$key]['object']=$type1_name.'-'.$type2_name.'-'.$type3_name.'-'.$type4_name;
            $where1['chapter_id']=array('eq',$value['id']);
            $course_lst=$course_model->where($where1)->select();
            $hours_lst=array_column($course_lst,'hours');
            $chapter_hours=array_sum($hours_lst);
            $data[$key]['hours']=$chapter_hours;
            if($value['type4_id']!=''){
                $data[$key]['max_hours']=D('type4')->find($value['type4_id'])['hours'];
            }else{
                if($value['type3_id']!=''){
                    $data[$key]['max_hours']=D('type3')->find($value['type3_id'])['hours'];
                }else{
                    $data[$key]['max_hours']=D('type2')->find($value['type2_id'])['hours'];
                }
            }
        }
        $this->assign('data',$data);
        $this->assign('type1',$type1);
        $this->display();
    }

    //修改章节信息
    public function edit_chapter(){
        $model=D('chapter');
        $id=I('get.id');
        $data=$model->find($id);
        $type1=D('type1')->field('id,type1_name')->select();
        $type2=D('type2')->field('id,type2_name')->select();
        $type3=D('type3')->field('id,type3_name')->select();
        $type4=D('type4')->field('id,type4_name')->select();
        if(!empty(I('post.'))){
           $save=$model->create();
           $save['id']=$id;
           $result=$model->save($save);
           if($result){
               $this->success('修改成功！',U('chapter_lst'));
           }else{
               $this->error('修改失败',U('chapter_lst'));
           }
        }
        $this->assign('data',$data);
        $this->assign('type1',$type1);
        $this->assign('type2',$type2);
        $this->assign('type3',$type3);
        $this->assign('type4',$type4);
        $this->display();
    }

    //添加章节信息
    public function add_chapter(){
        $model=D('chapter');
        if(!empty(I('post.'))){
            $add=$model->create();
            $add['update_time']=time();
            $result=$model->add($add);
            if($result){
                $this->success('添加成功！',U('chapter_lst'));
            }else{
                $this->error('添加失败!');
            }
        }
        $type1=D('type1')->select();
        $this->assign('type1',$type1);
        $this->display();
    }

    //删除章节信息
    public function del_chapter(){
        $model=D('chapter');
        $course_model=D('course');
        $id=I('get.id');
        $where['chapter_id']=array('eq',$id);
        $course_lst=$course_model->field('id')->where($where)->select();
        if(!empty($course_lst)){
            $this->error('请先删除该章节下的视频！',U('chapter_lst'));
        }else{
            $result=$model->delete($id);
            if($result){
                $this->success('删除成功！',U('chapter_lst'));
            }else{
                $this->error('删除失败',U('chapter_lst'));
            }
        }
    }
    public function tjlst(){
        $course_model=D('course');
//        if(!empty(I('get.id'))){
//            $id=I('get.id');
//            session('chapter_id',$id);
//        }else{
//            $id=session('chapter_id');
//        }
        $where['open']=array('eq',1);
        $type1=D('type1')->select();
        if(!empty(I('get.'))){
            if(!empty(I('get.title'))){
                $where['title']=array('like','%'.I('get.title').'%');
            }
        }
        $count=$course_model->where($where)->count();
        $page = new \Think\Page($count,10);
        $page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('last', '末页');
        $page->setConfig('first', '首页');
        $page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
        $page_show = $page->show();
        $this->assign('page',$page_show);
        $course_list=$course_model->where($where)->limit($page->firstRow,$page->listRows)->select();
        $this->assign('data',$course_list);
        $this->assign('type1',$type1);
        $this->display();
    }


    public function boutiquelst(){
        $course_model=D('course');
//        if(!empty(I('get.id'))){
//            $id=I('get.id');
//            session('chapter_id',$id);
//        }else{
//            $id=session('chapter_id');
//        }
        $where['open']=array('eq',2);
        $type1=D('type1')->select();
        if(!empty(I('get.'))){
            if(!empty(I('get.title'))){
                $where['title']=array('like','%'.I('get.title').'%');
            }
        }
        $count=$course_model->where($where)->count();
        $page = new \Think\Page($count,10);
        $page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('last', '末页');
        $page->setConfig('first', '首页');
        $page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
        $page_show = $page->show();
        $this->assign('page',$page_show);
        $course_list=$course_model->where($where)->limit($page->firstRow,$page->listRows)->select();
        $this->assign('data',$course_list);
        $this->assign('type1',$type1);
        $this->display();
    }

    //课程列表
    public function lst(){
        $course_model=D('course');
        if(!empty(I('get.id'))){
            $id=I('get.id');
            session('chapter_id',$id);
        }else{
            $id=session('chapter_id');
        }

        $where['chapter_id']=array('eq',$id);
        $type1=D('type1')->select();
        if(!empty(I('get.'))){
            if(!empty(I('get.title'))){
                $where['title']=array('like','%'.I('get.title').'%');
            }
        }
        $count=$course_model->where($where)->count();
        $page = new \Think\Page($count,10);
        $page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('last', '末页');
        $page->setConfig('first', '首页');
        $page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
        $page_show = $page->show();
        $this->assign('page',$page_show);
        $course_list=$course_model->where($where)->limit($page->firstRow,$page->listRows)->select();
        $this->assign('data',$course_list);
        $this->assign('type1',$type1);
        $this->display();
    }

    //添加课程
    public function old_add_course(){
        $model=D('course');
        $chapter_model=D('chapter');
        $chapter_id=session('chapter_id');
        $chapter=$chapter_model->find($chapter_id);
        if($chapter['type4_id']!=''){
            $max_hours=D('type4')->find($chapter['type4_id'])['hours'];
        }else{
            if($chapter['type3_id']!=''){
                $max_hours=D('type3')->find($chapter['type3_id'])['hours'];
            }else{
                $max_hours=D('type2')->find($chapter['type2_id'])['hours'];
            }
        }
        $where['type1_id']=array('eq',$chapter['type1_id']);
        $where['type2_id']=array('eq',$chapter['type2_id']);
        $where['type3_id']=array('eq',$chapter['type3_id']);
        $where['type4_id']=array('eq',$chapter['type4_id']);
        $chapter_lst=$chapter_model->field('id')->where($where)->select();
        $chapter_id_lst=array_column($chapter_lst,'id');
        $where1['chapter_id']=array('in',$chapter_id_lst);
        $course_lst=$model->field('hours')->where($where)->select();
        $course_hours=array_column($course_lst,'hours');
        $hours_sum=array_sum($course_hours);
        if(!empty(I('post.'))){
            $add=$model->create();
            if(($add['hours']+$hours_sum)>$max_hours){
                $this->error('添加课时已超过该工种最大课时！',U('lst?id='.$chapter_id));
            }else{
                if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0){
                    $upload = new \Think\Upload();// 实例化上传类
                    $upload->maxSize   =     31457280 ;// 设置附件上传大小
                    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                    $upload->rootPath  =      './Public/Uploads/kechengfengmian/'; // 设置附件上传根目录
                    // 上传单个文件
                    $info   =   $upload->uploadOne($_FILES['logo']);
                    $savepath=$info['savepath'].$info['savename'];
                    if(!$info) {// 上传错误提示错误信息
                        echo $upload->getError();
                    }else{// 上传成功
                        //获取上传文件信息
                        $savepath=$info['savepath'].$info['savename'];
                    }
                }
                $add['logo']=$savepath;
                $add['chapter_id']=$chapter_id;
                $result=$model->add($add);
                if($result){
                    $this->success('添加成功！',U('lst?id='.$chapter_id));
                }else{
                    $this->error('添加失败！',U('lst?id='.$chapter_id));
                }
            }
        }else{
            $this->display();
        }
    }

    //添加精选视频
    public function add_boutiquecourse(){
        $model=D('course');
        $chapter_model=D('chapter');
//        $chapter_id=session('chapter_id');
//        $chapter=$chapter_model->find($chapter_id);
//        if($chapter['type4_id']!=''){
//            $max_hours=D('type4')->find($chapter['type4_id'])['hours'];
//        }else{
//            if($chapter['type3_id']!=''){
//                $max_hours=D('type3')->find($chapter['type3_id'])['hours'];
//            }else{
//                $max_hours=D('type2')->find($chapter['type2_id'])['hours'];
//            }
//        }
//        $where['type1_id']=array('eq',$chapter['type1_id']);
//        $where['type2_id']=array('eq',$chapter['type2_id']);
//        $where['type3_id']=array('eq',$chapter['type3_id']);
//        $where['type4_id']=array('eq',$chapter['type4_id']);
//        $chapter_lst=$chapter_model->field('id')->where($where)->select();
//        $chapter_id_lst=array_column($chapter_lst,'id');
//        $where1['chapter_id']=array('in',$chapter_id_lst);
//        $course_lst=$model->field('hours')->where($where)->select();
//        $course_hours=array_column($course_lst,'hours');
//        $hours_sum=array_sum($course_hours);
        if(!empty(I('post.'))){
            $add=$model->create();
//            if(($add['hours']+$hours_sum)>$max_hours){
//                $this->error('添加课时已超过该工种最大课时！',U('lst?id='.$chapter_id));
//            }else{
            $video_info=$this->get_vinfo($add['video_id']);
            $add['logo']=$video_info->Video->CoverURL;
            $add['title']=$video_info->Video->Title;
            $add['content']=$video_info->Video->Description;
//            $add['chapter_id']=$chapter_id;
            $add['open']=2;
            $result=$model->add($add);
            if($result){
                $this->success('添加成功！',U('boutiquelst'));
            }else{
                $this->error('添加失败！',U('boutiquelst'));
//                }
            }
        }else{
            $this->display();
        }
    }

    //添加公开课视频
    public function add_opencourse(){
        $model=D('course');
        $chapter_model=D('chapter');
//        $chapter_id=session('chapter_id');
//        $chapter=$chapter_model->find($chapter_id);
//        if($chapter['type4_id']!=''){
//            $max_hours=D('type4')->find($chapter['type4_id'])['hours'];
//        }else{
//            if($chapter['type3_id']!=''){
//                $max_hours=D('type3')->find($chapter['type3_id'])['hours'];
//            }else{
//                $max_hours=D('type2')->find($chapter['type2_id'])['hours'];
//            }
//        }
//        $where['type1_id']=array('eq',$chapter['type1_id']);
//        $where['type2_id']=array('eq',$chapter['type2_id']);
//        $where['type3_id']=array('eq',$chapter['type3_id']);
//        $where['type4_id']=array('eq',$chapter['type4_id']);
//        $chapter_lst=$chapter_model->field('id')->where($where)->select();
//        $chapter_id_lst=array_column($chapter_lst,'id');
//        $where1['chapter_id']=array('in',$chapter_id_lst);
//        $course_lst=$model->field('hours')->where($where)->select();
//        $course_hours=array_column($course_lst,'hours');
//        $hours_sum=array_sum($course_hours);
        if(!empty(I('post.'))){
            $add=$model->create();
//            if(($add['hours']+$hours_sum)>$max_hours){
//                $this->error('添加课时已超过该工种最大课时！',U('lst?id='.$chapter_id));
//            }else{
            $video_info=$this->get_vinfo($add['video_id']);
            $add['logo']=$video_info->Video->CoverURL;
            $add['title']=$video_info->Video->Title;
            $add['content']=$video_info->Video->Description;
//            $add['chapter_id']=$chapter_id;
            $add['open']=1;
            $result=$model->add($add);
            if($result){
                $this->success('添加成功！',U('tjlst'));
            }else{
                $this->error('添加失败！',U('tjlst'));
//                }
            }
        }else{
            $this->display();
        }
    }

    //添加视频
    public function add_course(){
        $model=D('course');
        $chapter_model=D('chapter');
        $chapter_id=session('chapter_id');
        $chapter=$chapter_model->find($chapter_id);
        if($chapter['type4_id']!=''){
            $max_hours=D('type4')->find($chapter['type4_id'])['hours'];
        }else{
            if($chapter['type3_id']!=''){
                $max_hours=D('type3')->find($chapter['type3_id'])['hours'];
            }else{
                $max_hours=D('type2')->find($chapter['type2_id'])['hours'];
            }
        }
        $where['type1_id']=array('eq',$chapter['type1_id']);
        $where['type2_id']=array('eq',$chapter['type2_id']);
        $where['type3_id']=array('eq',$chapter['type3_id']);
        $where['type4_id']=array('eq',$chapter['type4_id']);
        $chapter_lst=$chapter_model->field('id')->where($where)->select();
        $chapter_id_lst=array_column($chapter_lst,'id');
        $where1['chapter_id']=array('in',$chapter_id_lst);
        $course_lst=$model->field('hours')->where($where)->select();
        $course_hours=array_column($course_lst,'hours');
        $hours_sum=array_sum($course_hours);
        if(!empty(I('post.'))){
            $add=$model->create();
//            if(($add['hours']+$hours_sum)>$max_hours){
//                $this->error('添加课时已超过该工种最大课时！',U('lst?id='.$chapter_id));
//            }else{
                $video_info=$this->get_vinfo($add['video_id']);
                $add['logo']=$video_info->Video->CoverURL;
                $add['title']=$video_info->Video->Title;
                $add['content']=$video_info->Video->Description;
                $add['chapter_id']=$chapter_id;
                $result=$model->add($add);
                if($result){
                    $this->success('添加成功！',U('lst?id='.$chapter_id));
                }else{
                    $this->error('添加失败！',U('lst?id='.$chapter_id));
//                }
            }
        }else{
            $this->display();
        }
    }


    //编辑课程
    public function edit_course(){
        $model=D('course');
        $chapter_model=D('chapter');
        $chapter_id=session('chapter_id');
        $chapter=$chapter_model->find($chapter_id);
        if($chapter['type4_id']!=''){
            $max_hours=D('type4')->find($chapter['type4_id'])['hours'];
        }else{
            if($chapter['type3_id']!=''){
                $max_hours=D('type3')->find($chapter['type3_id'])['hours'];
            }else{
                $max_hours=D('type2')->find($chapter['type2_id'])['hours'];
            }
        }
        $where['type1_id']=array('eq',$chapter['type1_id']);
        $where['type2_id']=array('eq',$chapter['type2_id']);
        $where['type3_id']=array('eq',$chapter['type3_id']);
        $where['type4_id']=array('eq',$chapter['type4_id']);
        $chapter_lst=$chapter_model->field('id')->where($where)->select();
        $chapter_id_lst=array_column($chapter_lst,'id');
        $where1['chapter_id']=array('in',$chapter_id_lst);
        $course_lst=$model->field('hours')->where($where)->select();
        $course_hours=array_column($course_lst,'hours');
        $hours_sum=array_sum($course_hours);
        $id=I('get.id');
        $course=$model->find($id);
        if(!empty(I('post.'))){
            $save=$model->create(I('post.'));
            if(($save['hours']+$hours_sum)>$max_hours){
                $this->error('修改课时已超过该工种最大课时！',U('lst?id='.$chapter_id));
            }else{
                if($course['video_id']!=$save['video_id']){
                    $video_info=$this->get_vinfo($save['video_id']);
                    $save['logo']=$video_info->Video->CoverURL;
                    $save['title']=$video_info->Video->Title;
                    $save['content']=$video_info->Video->Description;
                }
                $where['id']=array('eq',$id);
                $result=$model->where($where)->save($save);
                if($result){
                    $this->success('修改成功！',U('lst'));
                }else{
                    $this->error('修改失败！',U('lst'));
                }
            }
        }else{
            $this->assign('data',$course);
            $this->display();
        }
    }

    //删除课程
    public function del_course(){
        $model=D('course');
        $id=I('get.id');
        $result=$model->delete($id);
        if($result){
            $this->success('删除成功！',U('lst'));
        }else{
            $this->error('删除失败',U('lst'));
        }
    }

    //查看课程进度
    public function jindu(){
        $id=I('get.id');
        $course_record_model=D('course_record');
        $where['a.course_id']=array('eq',$id);
        if(!empty(I('post.name'))){
            $where['c.name']=array('like','%'.I('post.name').'%');
            $data=$course_record_model->alias('a')->field('a.is_finish,b.code,c.name,c.phonenum')->join('left join person b on a.person_id=b.id')->join('left join personinfo c on b.personinfoid=c.id')->where($where)->select();
        }else{
            $data=$course_record_model->alias('a')->field('a.is_finish,b.code,c.name,c.phonenum')->join('left join person b on a.person_id=b.id')->join('left join personinfo c on b.personinfoid=c.id')->where($where)->select();
        }
        foreach ($data as $k=>$v){
            if($v['is_finish']==0){
                $data[$k]['is_finish']='未完成';
            }else{
                $data[$k]['is_finish']='完成';
            }
        }
        $count_person=count($data);
        $where2['is_finish']=array('eq',1);
        $where2['course_id']=array('eq',$id);
        $count_over=$course_record_model->where($where2)->count();
        $this->assign('data',$data);
        $this->assign('over',$count_over);
        $this->assign('count',$count_person);
        $this->display();
    }

    //点评列表
    public function course_comment(){
        $model=D('course_comment');
        $comment=$model->group('course_id')->select();
        $count=count($comment);
        $num=10;
        $page = new \Think\Page($count,$num);
        $page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('last', '末页');
        $page->setConfig('first', '首页');
        $page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
        $page_show = $page->show();
        $this->assign('page',$page_show);
        if(!empty(I('get.title'))){
            $where['title']=array('like','%'.I('get.title').'%');
        }
        $data=$model->alias('a')->field('b.id,b.title')->join('left join course b on a.course_id=b.id')->limit($page->firstRow.','.$page->listRows)->where($where)->group('course_id')->select();
        $this->assign('data',$data);
        $this->display();
    }

    //查看课程点评
    public function comment_lst(){
        $model=D('course_comment');
        $id=I('get.id');
        $where['course_id']=array('eq',$id);
        $count=$model->where($where)->count();
        $num=20;
        $page = new \Think\Page($count,$num);
        $page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('last', '末页');
        $page->setConfig('first', '首页');
        $page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
        $page_show = $page->show();
        $this->assign('page',$page_show);
        $data=$model->alias('a')->field('a.id,a.content,a.ctime,a.is_set,b.name,c.title')->join('left join personinfo b on b.id=a.personinfo_id')->join('left join course c on c.id=a.course_id')->limit($page->firstRow.','.$page->listRows)->order('a.is_set desc ,a.ctime desc')->where($where)->select();
        foreach ($data as $k=>$v){
            $data[$k]['ctime']=date('Y-m-d H:i',$data[$k]['ctime']);
        }
        $this->assign('data',$data);
        $this->display();
    }

    //删除点评
    public function  del(){
        $model=D('course_comment');
        $id=I('get.id');
        $model->delete($id);
        $this->success('删除成功',U('comment_lst'),2);
    }

    //批量删除点评
    public function piliangDel(){
        $arr=I('post.json');
        $model=D('course_comment');
        foreach ($arr as $key => $value) {
            $model->delete($value[0]);
        }
        echo "true";
    }
    //置顶操作
    public function add_set(){
        $id=I('post.id');
        $model=D('course_comment');
        $where['id']=array('eq',$id);
        $save['is_set']=1;
        $model->where($where)->save($save);
    }

    //取消置顶
    public function del_set(){
        $id=I('post.id');
        $model=D('course_comment');
        $where['id']=array('eq',$id);
        $save['is_set']=0;
        $model->where($where)->save($save);
    }


    //测试获取视频信息
    //初始化客户端
    function init_vod_client($accessKeyId, $accessKeySecret) {
        $regionId = 'cn-shanghai';  // 点播服务所在的Region，国内请填cn-shanghai，不要填写别的区域
        $profile = \DefaultProfile::getProfile($regionId, $accessKeyId, $accessKeySecret);
        return new \DefaultAcsClient($profile);
    }

    //获取视频信息接口
    function get_video_info($client, $videoId) {
        $request = new vod\GetVideoInfoRequest();
        $request->setVideoId($videoId);
        $request->setAcceptFormat('JSON');
        return $client->getAcsResponse($request);
    }

    //测试获取视频信息
    public function get_vinfo($video_id){
        try {
            $client = $this->init_vod_client('LTAI3b06LiMb0ijJ', 'QBsBgoM167A6klkfVZ8AR4ONIJhLOT');
            $videoInfo = $this->get_video_info($client,$video_id);
            return $videoInfo;
//            var_dump($videoInfo->Video->CoverURL);
        } catch (Exception $e) {
            print $e->getMessage()."\n";
        }
    }

    //测试获取视频信息
    public function get_info(){
        try {
            $client = $this->init_vod_client('LTAI3b06LiMb0ijJ', 'QBsBgoM167A6klkfVZ8AR4ONIJhLOT');
            $videoInfo = $this->get_video_info($client,'9bf6b91e443b4109ab56ee26a471ff02');
//            return $videoInfo;
            var_dump($videoInfo->Video->CoverURL);
        } catch (Exception $e) {
            print $e->getMessage()."\n";
        }
    }
}
