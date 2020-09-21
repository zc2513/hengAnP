<?php
namespace Wechat\Controller;
require_once './api_sdk/vendor/autoload.php';

use \Wechat\Controller\BaseController;

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;
use Think\Log;
Config::load();

class IndexController extends BaseController {

    public function  isClass($person , $personcount ,$classid ){
        for($i = 0 ;$i < $personcount ; $i++){
               if($person[$i]['is_class'] == 1  && $person[$i]['class_id'] == $classid){
                    return true;
                }
           }
        }

    public function person(){
        if(empty(session('diy_id'))){
            session('diy_id',12);
        }

        if (session('diy_id') == 38){
            $psInfo=D('personinfo')->where('id='.session('id'))->find();
            if (!$psInfo['phonenum']||!$psInfo['area']){
                $this->redirect('Index/regedit38');
                exit;
            }
        }
        $id=session('id');
        $where['personinfoid']=array('eq',$id);
        $person=M('person')->where($where)->order('active_time desc')->select();
        $person_count=count($person);
        //用户绑定班主任
        if(!empty(session('class_id'))){
            $isclass = $this -> isClass($person,$person_count,session('class_id'));
            if($isclass !=  true){
                $classResult = M("class") -> where(array('id' => session('class_id'))) -> find();
                if($classResult){
                    $data['personinfoid'] = $id;
                    $data['diy_id'] = session('diy_id');
                    $data['is_use'] = 1;
                    $data['active_time'] = time();
                    $data['type1_id'] = $classResult['type1_id'];
                    $data['type2_id'] = $classResult['type2_id'];
                    $data['type3_id'] = $classResult['type3_id'];
                    $data['type4_id'] = $classResult['type4_id'];
                    $data['days'] = 120;
                    $data['is_class'] = 1;
                    $data['class_id'] = session('class_id');
                    M("person") -> add($data);
                    M("class_person") -> add(array("manager_id" => session('diy_id'),'class_id' =>  session('class_id'),"person_id" =>$id,'add_time' => time() ));
                }
            }
            session('class_id',null);
        }
        $this -> display("wechat/index");
    }


    public function index(){
        if(empty(session('diy_id'))){
            session('diy_id',12);
        }

        if (session('diy_id') == 38){
            $psInfo=D('personinfo')->where('id='.session('id'))->find();
            if (!$psInfo['phonenum']||!$psInfo['area']){
                $this->redirect('Index/regedit38');
                exit;
            }
        }
        $diyData = D('diy')->where('id=' . session('diy_id'))->find();
        $this->assign('diy_is_show',$diyData['is_show']);
        $lunbotu=D('diy_lunbotu')->where('diy_id='.session('diy_id'))->select();
        $personModel=D('person');
        $examModel=D('exam_record');             //考试记录
        $testModel=D('test_record');           //进度记录
        $chapterModel=D('chapter');            //章
        $courseModel=D('course');             //科目表
        $course_record_model=D('course_record');
        $id=session('id');
        $where['personinfoid']=array('eq',$id);
        $person=$personModel->where($where)->order('active_time desc')->select();
        $person_count=count($person);
        //卡号有效期
        $num=D('days')->find(1)['num'];
//        //用户绑定班主任
//        if(!empty(session('class_id'))){
//            $isclass = $this -> isClass($person,$person_count,session('class_id'));
//            if($isclass !=  true){
//                $classResult = M("class") -> where(array('id' => session('class_id'))) -> find();
//                if($classResult){
//                    $data['personinfoid'] = $id;
//                    $data['diy_id'] = session('diy_id');
//                    $data['is_use'] = 1;
//                    $data['active_time'] = time();
//                    $data['type1_id'] = $classResult['type1_id'];
//                    $data['type2_id'] = $classResult['type2_id'];
//                    $data['type3_id'] = $classResult['type3_id'];
//                    $data['type4_id'] = $classResult['type4_id'];
//                    $data['days'] = 120;
//                    $data['is_class'] = 1;
//                    $data['class_id'] = session('class_id');
//                    M("person") -> add($data);
//                }
//             }
//            session('class_id',null);
//        }
        //用户已购买学习卡
        if(!empty($person)){
            if(!empty(session('personid'))){
                $personid=session('personid');
                $where1['id']=array('eq',$personid);
                $data=$personModel->where($where1)->find();
                session('person_type',$data['type']);
                $active_time=$data['active_time'];
                if(empty($data['days'])){
                    $end_time=strtotime("+$num days",$active_time);
                }else{
                    $day=$data['days'];
                    $end_time=strtotime("+$day days",$active_time);
                }
                $data['end_time']=$end_time;
                //学习卡的截止日期
                if($end_time<time()){
                    $data['left_day']=0;
                }else{
                    $data['left_day']=ceil(($end_time-time())/(60*60*24));
                }
                //绑定科目
                $object=D('type4')->where('id='.$data['type4_id'])->find();
                if($object['type4_name']=='无'){
                    $title=D('type3')->where('id='.$data['type3_id'])->find()['type3_name'];
                    session('object',$title);
                }else{
                    $title=$object['type4_name'];
                    session('object',$title);
                }
                //设置科目标题
                $type1_name=D('type1')->field('type1_name')->where('id='.$data['type1_id'])->find()['type1_name'];
                $type2_name=D('type2')->field('type2_name')->where('id='.$data['type2_id'])->find()['type2_name'];
                $type3_name=D('type3')->field('type3_name')->where('id='.$data['type3_id'])->find()['type3_name'];
                $type4_name=D('type4')->field('type4_name')->where('id='.$data['type4_id'])->find()['type4_name'];
                $atitle=$type1_name.'--'.$type2_name.'--'.$type3_name.'--'.$type4_name;
                session('title',$atitle);
                //模拟考试信息
                //模拟试卷数量
                $where6['is_submit']=['eq',1];
                $count=$examModel->where('person_id='.$personid)->where($where6)->count();
                //最高分数
                if($count==0){
                    $max_score=0;
                }else{
                    $max_score=$examModel->where('person_id='.$personid)->max('score');
                }
                //练习进度
                $jindu=$testModel->where('person_id='.$personid)->find()['jindu'];
                if(empty($jindu)){
                    $jindu=0;
                }
                //绑定科目对应题目数量
                $where2['type1_id']=array('eq',$data['type1_id']);
                $where2['type2_id']=array('eq',$data['type2_id']);
                $where2['type3_id']=array('eq',$data['type3_id']);
                $where2['type4_id']=array('eq',$data['type4_id']);

                $where7['test_type']=array('eq',$data['type5']);
                $where7['address']=array('eq',$data['type6']);

                $where8['test_type']=array('eq',"初训");
                $where8['address']=array('eq',"国家题库");
                //----
                if ($data['type6']!='国家题库'){
                    $luojiSettingsInfo=D('luoji_settings')->where("provincial='".$data['type6']."'")->find();
                    if ($data['type5']=='初训'){//初训
                        $practice = $luojiSettingsInfo['fi_practice'];
                    }else{ //复训
                        $practice = $luojiSettingsInfo['seq_practice'];
                    }

                    if ($practice<2){//国家题库
                        $where7['test_type']=array('eq',"初训");
                        $where7['address']=array('eq','国家题库');
                    }else if($practice==2){//地方题库

                    }else if($practice==3){//国家、地方题库
                        $where7="(address ='".$data['type6']."' and test_type ='".$data['type5']."') or (address='国家题库' and test_type ='初训')";//
                    }
                }
                //----
                $allCount=D('selectanswer')->where($where2)->where($where7)->count();
                if($allCount == 0){
                    $allCount=D('selectanswer')->where($where2)->where($where8)->count();
                }

                //绑定科目对应的课程的总课时
                $chapter_lst=$chapterModel->field('id')->where($where2)->select();
                if(!empty($chapter_lst)){
                    $chapter_id_lst=array_column($chapter_lst,'id');
                    $where3['chapter_id']=array('in',$chapter_id_lst);
                    $course_lst=$courseModel->where($where3)->select();
                    $course_hours=array_column($course_lst,'hours');
                    $chapter_hours=array_sum($course_hours);
                }else{
                    $chapter_hours=0;
                }


                //已完成课时
                $where4['person_id']=array('eq',$personid);
                $where4['is_finish']=array('eq',1);
                $course_record_lst=$course_record_model->field('course_id')->where($where4)->select();
                if(!empty($course_record_lst)){
                    $course_id_lst=array_column($course_record_lst,'course_id');
                    $where5['id']=array('in',$course_id_lst);
                    $finish_course=$courseModel->where($where5)->select();
                    $finish_hours=array_column($finish_course,'hours');
                    $chapter_record_hours=array_sum($finish_hours);
                }else{
                    $chapter_record_hours=0;
                }
            }else{
                $data=$person[0];
                session('personid',$data['id']);
                session('person_type',$data['type']);
                $active_time=$data['active_time'];
                if(empty($data['days'])){
                    $end_time=strtotime("+$num days",$active_time);
                }else{
                    $day=$data['days'];
                    $end_time=strtotime("+$day days",$active_time);
                }
                $data['end_time']=$end_time;
                //学习卡的截止日期
                if($end_time<time()){
                    $data['left_day']=0;
                }else{
                    $data['left_day']=ceil(($end_time-time())/(60*60*24));
                }
                $object=D('type4')->where('id='.$data['type4_id'])->find();
                if($object['type4_name']=='无'){
                    $title=D('type3')->where('id='.$data['type3_id'])->find()['type3_name'];
                    session('object',$title);
                }else{
                    $title=$object['type4_name'];
                    session('object',$title);
                }
                //设置科目标题
                $type1_name=D('type1')->field('type1_name')->where('id='.$data['type1_id'])->find()['type1_name'];
                $type2_name=D('type2')->field('type2_name')->where('id='.$data['type2_id'])->find()['type2_name'];
                $type3_name=D('type3')->field('type3_name')->where('id='.$data['type3_id'])->find()['type3_name'];
                $type4_name=D('type4')->field('type4_name')->where('id='.$data['type4_id'])->find()['type4_name'];
                $atitle=$type1_name.'--'.$type2_name.'--'.$type3_name.'--'.$type4_name;
                session('title',$atitle);
                //获得绑定科目的题目数量
                $where6['is_submit']=['eq',1];
                $count=$examModel->where('person_id='.$data['id'])->where($where6)->count();
                if($count==0){
                    $max_score=0;
                }else{
                    $max_score=$examModel->where('person_id='.$data['id'])->max('score');
                }
                $jindu=$testModel->where('person_id='.$data['id'])->find()['jindu'];
                if(empty($jindu)){
                    $jindu=0;
                }
                $where2['type1_id']=array('eq',$data['type1_id']);
                $where2['type2_id']=array('eq',$data['type2_id']);
                $where2['type3_id']=array('eq',$data['type3_id']);
                $where2['type4_id']=array('eq',$data['type4_id']);

                $where7['test_type']=array('eq',$data['type5']);
                $where7['address']=array('eq',$data['type6']);

                $where8['test_type']=array('eq',"初训");
                $where8['address']=array('eq',"国家题库");
                //----
                if ($data['type6']!='国家题库'){
                    $luojiSettingsInfo=D('luoji_settings')->where("provincial='".$data['type6']."'")->find();
                    if ($data['type5']=='初训'){//初训
                        $practice = $luojiSettingsInfo['fi_practice'];
                    }else{ //复训
                        $practice = $luojiSettingsInfo['seq_practice'];
                    }

                    if ($practice<2){//国家题库
                        $where7['test_type']=array('eq',"初训");
                        $where7['address']=array('eq','国家题库');
                    }else if($practice==2){//地方题库

                    }else if($practice==3){//国家、地方题库
                        $where7="(address ='".$data['type6']."' and test_type ='".$data['type5']."') or (address='国家题库' and test_type ='初训')";//
                    }
                }
                //----
                $allCount=D('selectanswer')->where($where2)->where($where7)->count();
                if($allCount == 0){
                    $allCount=D('selectanswer')->where($where2)->where($where8)->count();
                }

                //绑定科目对应的课程的总课时
                $chapter_lst=$chapterModel->field('id')->where($where2)->select();
                if(!empty($chapter_lst)){
                    $chapter_id_lst=array_column($chapter_lst,'id');
                    $where3['chapter_id']=array('in',$chapter_id_lst);
                    $course_lst=$courseModel->where($where3)->select();
                    $course_hours=array_column($course_lst,'hours');
                    $chapter_hours=array_sum($course_hours);
                }else{
                    $chapter_hours=0;
                }


                //已完成课时
                $where4['person_id']=array('eq',session('personid'));
                $where4['is_finish']=array('eq',1);
                $course_record_lst=$course_record_model->field('course_id')->where($where4)->select();
                if(!empty($course_record_lst)){
                    $course_id_lst=array_column($course_record_lst,'course_id');
                    $where5['id']=array('in',$course_id_lst);
                    $finish_course=$courseModel->where($where5)->select();
                    $finish_hours=array_column($finish_course,'hours');
                    $chapter_record_hours=array_sum($finish_hours);
                }else{
                    $chapter_record_hours=0;
                }

            }
            //用户未购买学习卡
        }else{
//            echo $id;
            $nobuyModel=D('nobuy');
            $nobuy=$nobuyModel->where('personinfoid='.$id)->find();
//            dump($nobuy);
//            exit;
            if(!empty($nobuy)){
                $jindu=0;
                $allCount=0;
                $max_score=0;
                session('test_id',$nobuy['id']);
                $object=D('type4')->where('id='.$nobuy['type4_id'])->find();
                if($object['type4_name']=='无'){
                    $title=D('type3')->where('id='.$nobuy['type3_id'])->find()['type3_name'];
                }else{
                    $title=$object['type4_name'];
                }
                $data='';
                $count=0;
                $chapter_hours=0;
                $chapter_record_hours=0;
            }else{
                $jindu=0;
                $allCount=0;
                $max_score=0;
                $title='';
                $data='';
                $count=0;
                $chapter_hours=0;
                $chapter_record_hours=0;
            }
        }
        $this->assign('nobuy',$nobuy);
        $this->assign('person_count',$person_count);
        $this->assign('jindu',$jindu);
        $this->assign('all_count',$allCount);
        $this->assign('max_score',$max_score);
        $this->assign('title',$title);
        $this->assign('data',$data);
        $this->assign('count',$count);
        $this->assign('lunbotu',$lunbotu);
        $this->assign('chapter_hours',$chapter_hours);
        $this->assign('chapter_record_hours',$chapter_record_hours);
        $this->display();
    }


    public function personstudy(){
        if(empty(session('diy_id'))){
            session('diy_id',12);
        }

        if (session('diy_id') == 38){
            $psInfo=D('personinfo')->where('id='.session('id'))->find();
            if (!$psInfo['phonenum']||!$psInfo['area']){
                $this->redirect('Index/regedit38');
                exit;
            }
        }
        $diyData = D('diy')->where('id=' . session('diy_id'))->find();
        $this->assign('diy_is_show',$diyData['is_show']);
        $lunbotu=D('diy_lunbotu')->where('diy_id='.session('diy_id'))->select();
        $personModel=D('person');
        $examModel=D('exam_record');             //考试记录
        $testModel=D('test_record');           //进度记录
        $chapterModel=D('chapter');            //章
        $courseModel=D('course');             //科目表
        $course_record_model=D('course_record');
        $id=session('id');
        $where['personinfoid']=array('eq',$id);
        $person=$personModel->where($where)->order('active_time desc')->select();
        $person_count=count($person);
        //卡号有效期
        $num=D('days')->find(1)['num'];
        //用户绑定班主任
        if(!empty(session('class_id'))){
            $isclass = $this -> isClass($person,$person_count,session('class_id'));
            if($isclass !=  true){
                $classResult = M("class") -> where(array('id' => session('class_id'))) -> find();
                if($classResult){
                    $data['personinfoid'] = $id;
                    $data['diy_id'] = session('diy_id');
                    $data['is_use'] = 1;
                    $data['active_time'] = time();
                    $data['type1_id'] = $classResult['type1_id'];
                    $data['type2_id'] = $classResult['type2_id'];
                    $data['type3_id'] = $classResult['type3_id'];
                    $data['type4_id'] = $classResult['type4_id'];
                    $data['days'] = 120;
                    $data['is_class'] = 1;
                    $data['class_id'] = session('class_id');
                    M("person") -> add($data);
                }
            }
            session('class_id',null);
        }
        //用户已购买学习卡
        if(!empty($person)){
            if(!empty(session('personid'))){
                $personid=session('personid');
                $where1['id']=array('eq',$personid);
                $data=$personModel->where($where1)->find();
                session('person_type',$data['type']);
                $active_time=$data['active_time'];
                if(empty($data['days'])){
                    $end_time=strtotime("+$num days",$active_time);
                }else{
                    $day=$data['days'];
                    $end_time=strtotime("+$day days",$active_time);
                }
                $data['end_time']=$end_time;
                //学习卡的截止日期
                if($end_time<time()){
                    $data['left_day']=0;
                }else{
                    $data['left_day']=ceil(($end_time-time())/(60*60*24));
                }
//                dump($data);
                if($data['class_id'] != ""  &&  $data['is_class'] != '0'){
                    //绑定班级
                    $title= M("class")  ->  where(array("id" =>$data['class_id'])) -> field("class_name") -> find()['class_name'];
                    session('object',$title);
                }else{
                    //绑定科目
                    $object=D('type4')->where('id='.$data['type4_id'])->find();
                    if($object['type4_name']=='无'){
                        $title=D('type3')->where('id='.$data['type3_id'])->find()['type3_name'];
                        session('object',$title);
                    }else{
                        $title=$object['type4_name'];
                        session('object',$title);
                    }
                }

                //设置科目标题
                $type1_name=D('type1')->field('type1_name')->where('id='.$data['type1_id'])->find()['type1_name'];
                $type2_name=D('type2')->field('type2_name')->where('id='.$data['type2_id'])->find()['type2_name'];
                $type3_name=D('type3')->field('type3_name')->where('id='.$data['type3_id'])->find()['type3_name'];
                $type4_name=D('type4')->field('type4_name')->where('id='.$data['type4_id'])->find()['type4_name'];
                $atitle=$type1_name.'--'.$type2_name.'--'.$type3_name.'--'.$type4_name;
                session('title',$atitle);
                //模拟考试信息
                //模拟试卷数量
                $where6['is_submit']=['eq',1];
                $count=$examModel->where('person_id='.$personid)->where($where6)->count();
                //最高分数
                if($count==0){
                    $max_score=0;
                }else{
                    $max_score=$examModel->where('person_id='.$personid)->max('score');
                }
                //练习进度
                $jindu=$testModel->where('person_id='.$personid)->find()['jindu'];
                if(empty($jindu)){
                    $jindu=0;
                }
                //绑定科目对应题目数量
                $where2['type1_id']=array('eq',$data['type1_id']);
                $where2['type2_id']=array('eq',$data['type2_id']);
                $where2['type3_id']=array('eq',$data['type3_id']);
                $where2['type4_id']=array('eq',$data['type4_id']);

                $where7['test_type']=array('eq',$data['type5']);
                $where7['address']=array('eq',$data['type6']);

                $where8['test_type']=array('eq',"初训");
                $where8['address']=array('eq',"国家题库");
                //----
                if ($data['type6']!='国家题库'){
                    $luojiSettingsInfo=D('luoji_settings')->where("provincial='".$data['type6']."'")->find();
                    if ($data['type5']=='初训'){//初训
                        $practice = $luojiSettingsInfo['fi_practice'];
                    }else{ //复训
                        $practice = $luojiSettingsInfo['seq_practice'];
                    }

                    if ($practice<2){//国家题库
                        $where7['test_type']=array('eq',"初训");
                        $where7['address']=array('eq','国家题库');
                    }else if($practice==2){//地方题库

                    }else if($practice==3){//国家、地方题库
                        $where7="(address ='".$data['type6']."' and test_type ='".$data['type5']."') or (address='国家题库' and test_type ='初训')";//
                    }
                }
                //----
                $allCount=D('selectanswer')->where($where2)->where($where7)->count();
                if($allCount == 0){
                    $allCount=D('selectanswer')->where($where2)->where($where8)->count();
                }

                //绑定科目对应的课程的总课时
                $chapter_lst=$chapterModel->field('id')->where($where2)->select();
                if(!empty($chapter_lst)){
                    $chapter_id_lst=array_column($chapter_lst,'id');
                    $where3['chapter_id']=array('in',$chapter_id_lst);
                    $course_lst=$courseModel->where($where3)->select();
                    $course_hours=array_column($course_lst,'hours');
                    $chapter_hours=array_sum($course_hours);
                }else{
                    $chapter_hours=0;
                }


                //已完成课时
                $where4['person_id']=array('eq',$personid);
                $where4['is_finish']=array('eq',1);
                $course_record_lst=$course_record_model->field('course_id')->where($where4)->select();
                if(!empty($course_record_lst)){
                    $course_id_lst=array_column($course_record_lst,'course_id');
                    $where5['id']=array('in',$course_id_lst);
                    $finish_course=$courseModel->where($where5)->select();
                    $finish_hours=array_column($finish_course,'hours');
                    $chapter_record_hours=array_sum($finish_hours);
                }else{
                    $chapter_record_hours=0;
                }
            }else{
                $data=$person[0];
                session('personid',$data['id']);
                session('person_type',$data['type']);
                $active_time=$data['active_time'];
                if(empty($data['days'])){
                    $end_time=strtotime("+$num days",$active_time);
                }else{
                    $day=$data['days'];
                    $end_time=strtotime("+$day days",$active_time);
                }
                $data['end_time']=$end_time;
                //学习卡的截止日期
                if($end_time<time()){
                    $data['left_day']=0;
                }else{
                    $data['left_day']=ceil(($end_time-time())/(60*60*24));
                }
                $object=D('type4')->where('id='.$data['type4_id'])->find();
                if($object['type4_name']=='无'){
                    $title=D('type3')->where('id='.$data['type3_id'])->find()['type3_name'];
                    session('object',$title);
                }else{
                    $title=$object['type4_name'];
                    session('object',$title);
                }
                //设置科目标题
                $type1_name=D('type1')->field('type1_name')->where('id='.$data['type1_id'])->find()['type1_name'];
                $type2_name=D('type2')->field('type2_name')->where('id='.$data['type2_id'])->find()['type2_name'];
                $type3_name=D('type3')->field('type3_name')->where('id='.$data['type3_id'])->find()['type3_name'];
                $type4_name=D('type4')->field('type4_name')->where('id='.$data['type4_id'])->find()['type4_name'];
                $atitle=$type1_name.'--'.$type2_name.'--'.$type3_name.'--'.$type4_name;
                session('title',$atitle);
                //获得绑定科目的题目数量
                $where6['is_submit']=['eq',1];
                $count=$examModel->where('person_id='.$data['id'])->where($where6)->count();
                if($count==0){
                    $max_score=0;
                }else{
                    $max_score=$examModel->where('person_id='.$data['id'])->max('score');
                }
                $jindu=$testModel->where('person_id='.$data['id'])->find()['jindu'];
                if(empty($jindu)){
                    $jindu=0;
                }
                $where2['type1_id']=array('eq',$data['type1_id']);
                $where2['type2_id']=array('eq',$data['type2_id']);
                $where2['type3_id']=array('eq',$data['type3_id']);
                $where2['type4_id']=array('eq',$data['type4_id']);

                $where7['test_type']=array('eq',$data['type5']);
                $where7['address']=array('eq',$data['type6']);

                $where8['test_type']=array('eq',"初训");
                $where8['address']=array('eq',"国家题库");
                //----
                if ($data['type6']!='国家题库'){
                    $luojiSettingsInfo=D('luoji_settings')->where("provincial='".$data['type6']."'")->find();
                    if ($data['type5']=='初训'){//初训
                        $practice = $luojiSettingsInfo['fi_practice'];
                    }else{ //复训
                        $practice = $luojiSettingsInfo['seq_practice'];
                    }

                    if ($practice<2){//国家题库
                        $where7['test_type']=array('eq',"初训");
                        $where7['address']=array('eq','国家题库');
                    }else if($practice==2){//地方题库

                    }else if($practice==3){//国家、地方题库
                        $where7="(address ='".$data['type6']."' and test_type ='".$data['type5']."') or (address='国家题库' and test_type ='初训')";//
                    }
                }
                //----
                $allCount=D('selectanswer')->where($where2)->where($where7)->count();
                if($allCount == 0){
                    $allCount=D('selectanswer')->where($where2)->where($where8)->count();
                }

                //绑定科目对应的课程的总课时
                $chapter_lst=$chapterModel->field('id')->where($where2)->select();
                if(!empty($chapter_lst)){
                    $chapter_id_lst=array_column($chapter_lst,'id');
                    $where3['chapter_id']=array('in',$chapter_id_lst);
                    $course_lst=$courseModel->where($where3)->select();
                    $course_hours=array_column($course_lst,'hours');
                    $chapter_hours=array_sum($course_hours);
                }else{
                    $chapter_hours=0;
                }


                //已完成课时
                $where4['person_id']=array('eq',session('personid'));
                $where4['is_finish']=array('eq',1);
                $course_record_lst=$course_record_model->field('course_id')->where($where4)->select();
                if(!empty($course_record_lst)){
                    $course_id_lst=array_column($course_record_lst,'course_id');
                    $where5['id']=array('in',$course_id_lst);
                    $finish_course=$courseModel->where($where5)->select();
                    $finish_hours=array_column($finish_course,'hours');
                    $chapter_record_hours=array_sum($finish_hours);
                }else{
                    $chapter_record_hours=0;
                }

            }
            //用户未购买学习卡
        }else{
//            echo $id;
            $nobuyModel=D('nobuy');
            $nobuy=$nobuyModel->where('personinfoid='.$id)->find();
//            dump($nobuy);
//            exit;
            if(!empty($nobuy)){
                $jindu=0;
                $allCount=0;
                $max_score=0;
                session('test_id',$nobuy['id']);
                $object=D('type4')->where('id='.$nobuy['type4_id'])->find();
                if($object['type4_name']=='无'){
                    $title=D('type3')->where('id='.$nobuy['type3_id'])->find()['type3_name'];
                }else{
                    $title=$object['type4_name'];
                }
                $data='';
                $count=0;
                $chapter_hours=0;
                $chapter_record_hours=0;
            }else{
                $jindu=0;
                $allCount=0;
                $max_score=0;
                $title='';
                $data='';
                $count=0;
                $chapter_hours=0;
                $chapter_record_hours=0;
            }
        }


        //视频播放代码
        $course_model=D('course');
        $course_record_model=D('course_record');
        $person_model=D('person');
        $chapter_model=D('chapter');
        $person_id1=session('personid');
        $person_type=session('person_type');
        $person=$person_model->field('active_time,type1_id,type2_id,type3_id,type4_id,days')->where('id='.$person_id1)->find();
//        $object=D('type4')->where('id='.$person['type4_id'])->find();
//        if($object['type4_name']=='无'){
//            $title=D('type3')->where('id='.$person['type3_id'])->find()['type3_name'];
//            session('object',$title);
//        }else{
//            $title=$object['type4_name'];
//            session('object',$title);
//        }
        $wherechapter['type1_id']=array('eq',$person['type1_id']);
        $wherechapter['type2_id']=array('eq',$person['type2_id']);
        $wherechapter['type3_id']=array('eq',$person['type3_id']);
        $wherechapter['type4_id']=array('eq',$person['type4_id']);
        if($person_type !=1){
            $wherechapter['is_free']=array('eq',1);
        }
        $data=$chapter_model->field('id,chapter_title')->where($wherechapter)->select();
        session('chapter_id',$data[0]['id']);
        if(empty(I('get.id'))){
            $id=session('chapter_id');
        }else{
            $id=I('get.id');
            session('chapter_id',$id);
        }
        $person_id=session('personid');
        $person=$person_model->field('active_time,days')->where('id='.$person_id)->find();
        $where['chapter_id']=array('eq',$id);
        $course=$course_model->where($where)->select();
        foreach ($course as $key =>$value){
            $wherechapter['course_id']=array('eq',$value['id']);
            $wherechapter['person_id']=$person_id;
            $course_record=$course_record_model->where($wherechapter)->find();
            if(!empty($course_record)){
                if($course_record['is_finish']==0){
                    $course[$key]['is_finish']='未完成';
                }else{
                    $course[$key]['is_finish']='已完成';
                }
            }else{
                $add['person_id']=$person_id;
                $add['course_id']=$value['id'];
                $course_record_model->add($add);
                $course[$key]['is_finish']='未完成';
            }

        }
        $num=D('days')->find(1)['num'];
        $chapter=$chapter_model->field('id,chapter_title title')->find(session('chapter_id'));
        if(empty($person['days'])){
            $end_time=strtotime("+$num days",$person['active_time']);
        }else{
            $day=$person['days'];
            $end_time=strtotime("+$day days",$person['active_time']);
        }
        $this->assign('chapter',$chapter);
//        dump($chapter);
        $this->assign('end_time',$end_time);
//        dump($end_time);
        $this->assign('course',$course);
//        dump($course);
//        $this->assign('title',session('object'));

//        ****************************************************
        $this->assign('nobuy',$nobuy);
        $this->assign('person_count',$person_count);
        $this->assign('jindu',$jindu);
        $this->assign('all_count',$allCount);
        $this->assign('max_score',$max_score);
        $this->assign('title',$title);
        $this->assign('data',$data);
        $this->assign('count',$count);
        $this->assign('lunbotu',$lunbotu);
        $this->assign('chapter_hours',$chapter_hours);
        $this->assign('chapter_record_hours',$chapter_record_hours);
        $this->display("Wechat/study");
    }

    //课时详情
    public function hours_detail(){
        $person_id=session('personid');
        $person_model=D('person');
        $chapter_model=D('chapter');
        $course_model=D('course');
        $person=$person_model->find($person_id);
        $where['type1_id']=array('eq',$person['type1_id']);
        $where['type2_id']=array('eq',$person['type2_id']);
        $where['type3_id']=array('eq',$person['type3_id']);
        $where['type4_id']=array('eq',$person['type4_id']);
        $chapter_lst=$chapter_model->field('id,chapter_title')->where($where)->select();
        foreach ($chapter_lst as $key=>$value){
            $where1['a.chapter_id']=array('eq',$value['id']);
            $where1['b.person_id']=array('eq',$person_id);
            $course_lst=$course_model->alias('a')->field('a.id,a.title,a.hours,b.is_finish')->join('course_record b on b.course_id=a.id')->where($where1)->select();
            foreach ($course_lst as $k=>$v){
                if($v['is_finish']==0){
                    $course_lst[$k]['is_finish']='未完成';
                }else{
                    $course_lst[$k]['is_finish']='已完成';
                }
            }
            $chapter_lst[$key]['course_lst']=$course_lst;
        }
        $this->assign('data',$chapter_lst);
        $this->display();
    }


    //试用绑定界面
    public function test1(){
        $type1=D('type1')->select();
        $this->assign('type1',$type1);
        $this->display();
    }

    //试用绑定
    public function test_bind1(){
        $model=D('nobuy');
        $id=session('id');
        $where['personinfoid']=array('eq',$id);
        $testinfo=$model->field('id,personinfoid')->where($where)->find();
        if(empty($testinfo)){
            $data=$model->create();
            $data['personinfoid']=$id;
            $data['active_time']=time();
            if($model->add($data)){
                echo 1;
            }else{
                echo 0;
            }
        }else{
            echo 2;
        }
    }

    //卡密绑定
    public function card_bind(){
        $code = "";
        $password = "";
        if(!empty(I('get.code'))){
            $code = I('get.code');
        }
        if(!empty(I('get.password'))){
            $password = I('get.password');
        }
        $this->assign('code',$code);
        $this->assign('password',$password);



        $type1=D('type1')->select();
        $this->assign('type1',$type1);
        $this->display();
    }

    //验证卡号和密码是否正确
    public function check_card(){
        $code=I('post.code');
        $password=I('post.password');
        $model=D('person');
        $where['code']=['eq',$code];
        $person=$model->field('id,is_use,password')->where($where)->find();
        if(!empty($person)){
            if($person['password']==$password){
                if($person['is_use']==1){
                    //已被绑定
                    echo 2;
                }else{
                    //卡号和密码都正确
                    echo 1;
                }
            }else{
                //密码不正确
                echo 3;
            }
        }else{
            //该卡号不存在
            echo 4;
        }
    }

    public function bind(){
        $model=D('person');
        $data=$model->create();   //.create方法可以对POST提交的数据进行处理
        $data['personinfoid']=session('id');
        $data['is_use']=1;
        $data['diy_id']=session('diy_id');
        $data['active_time']=time();
        $code=$data['code'];
        $where['code']=array('eq',$code);
        $person=$model->field('id,password')->where($where)->find();
        $where1['id']=array('eq',$person['id']);
        if($model->where($where1)->save($data)){
            //绑定成功
            echo 1;
            session('personid',$person['id']);

            $wh['personinfoid'] =  array('eq',session('id'));
            $wh['person_id'] = array('eq',$person['id']);
            $wh['is_create_code'] = array('eq',0);
            $wh['is_success'] = array('eq',1);
            $res = D('online_order')->where($wh)->find();
            if($res){
                $w['id'] = array('eq',$res['id']);
                $up['is_create_code'] = 1;
                D('online_order')->where($w)->save($up);
            }

        }else{
            //绑定失败
            echo 0;
        }
    }
    public function gettype2(){
        $model=D('type2');
        $id=I('get.id');
        $data['type1']=D('type1')->find($id);
        $data['type2']=$model->where("type1_id=$id")->select();
        echo json_encode($data);
    }
    public function gettype3(){
        $model=D('type3');
        $id=I('get.id');
        $data=$model->where("type2_id=$id")->select();
        echo json_encode($data);
    }
    public function gettype4(){
        $model=D('type4');
        $id=I('get.id');
        $data=$model->where("type3_id=$id")->select();
        echo json_encode($data);
    }
    public function gettype5(){
        $data=I('get.');
        $where['type1_id'] = $data['ty1'];
        $where['type2_id'] = $data['ty2'];
        $where['type3_id'] = $data['ty3'];
        $where['type4_id'] = $data['ty4'];
        $where['test_type'] = $data['ty5'];
        $where['address'] = $data['ty6'];
        $res = D('selectanswer')->where($where)->find();
        if($res) {
            echo 1;
        }
        else{
            echo 0;
        }
    }
    public function gettype6(){
        $data=I('get.');
        $where['type1_id'] = $data['ty1'];
        $where['type2_id'] = $data['ty2'];
        $where['type3_id'] = $data['ty3'];
        $where['type4_id'] = $data['ty4'];
        $where['type5'] = $data['ty5'];
        $where['type6'] = $data['ty6'];
        $res = D('luoji')->where($where)->find();
        if($res) {
            echo 0;
        }
        else{
            echo 1;
        }
    }
    //购买会员
    public function buy(){
        $price=D('price')->find(1);
        $this->assign('price',$price);

        $this->display();
    }

    //切换科目
    public function change_obj(){
        $model=D('person');
        $id=session('id');
        $personid=session('personid');
        $personlst=$model->where('personinfoid='.$id)->select();
        $person=$model->find($personid);
        $count=count($personlst);
        if($count<=1){
            session('personid',$personid);
        }else{
            $num=array_keys($personlst,$person)[0];
            $next=$num+1;
            if($next>$count-1){
                $pid=$personlst[0]['id'];
            }else{
                $pid=$personlst[$next]['id'];
            }
            session('personid',$pid);
        }
        $this->redirect(U('index'));
    }


    //切换科目2
    public function change_person_obj(){
        $model=D('person');
        $id=session('id');
        $personid=session('personid');
        $personlst=$model->where('personinfoid='.$id)->select();
        $person=$model->find($personid);
        $count=count($personlst);
        if($count<=1){
            session('personid',$personid);
        }else{
            $num=array_keys($personlst,$person)[0];
            $next=$num+1;
            if($next>$count-1){
                $pid=$personlst[0]['id'];
            }else{
                $pid=$personlst[$next]['id'];
            }
            session('personid',$pid);
        }
        $this->redirect(U('Index/personstudy'));
    }



    //使用帮助
    public function help(){
        $model=D('help');
        $data=$model->find(1);
        $this->assign('data',$data);
        $this->display();
    }
    //机构介绍
    public function diy(){
        $model=D('diy_page');
        $where['diy_id']=array('eq',session('diy_id'));
        $where['title']=array('eq','机构介绍');
        $page=$model->where($where)->find();
        $this->assign('page',$page);
        $this->display();
    }
    //报名
    public function enroll(){
        if(IS_POST) {
            $model=D('enroll');
            $add['personinfo_id'] = session('id');
            $where['personinfo_id']=array('eq',session('id'));
            $count=$model->where($where)->count();
            if($count > 10){
                echo 3;
                exit;
            }
            $add['enroll_name'] = I('post.enroll_name');
            $add['enroll_mobile'] = I('post.enroll_mobile');
            $add['projectname'] = I('post.projectname');
            $add['diy_id'] = session('diy_id');
            $where = null;
            $where['enroll_name']=array('eq',$add['enroll_name']);
            $where['enroll_mobile']=array('eq',$add['enroll_mobile']);
            $where['projectname']=array('eq',$add['projectname']);
            $where['diy_id']=array('eq',$add['diy_id']);
            $count=$model->where($where)->count();
            if($count>0){
                echo 2;
                exit;
            }
            $add['remarks'] = I('post.remarks');
            $add['diy_id'] = session('diy_id');
            $add['enroll_time'] = time();
            //  $add = $model->create();
            if($model->add($add)){
                $getdiyMessageData = D('diy')->where('id=' . $add['diy_id'])->find();
                if($getdiyMessageData && $getdiyMessageData['text3']){
                    if(preg_match("/^1[3456789]{1}\d{9}$/",$getdiyMessageData['text3'])){
                        $remarks = $add['remarks'] ? $add['remarks'] : "无";
                        $this->sendenrollmessage($getdiyMessageData['text3'], $add['enroll_name'], $remarks, $add['projectname'],$add['enroll_mobile']);
                    }
                }
                echo 1;
                exit;
            }else{
                echo 0;
                exit;
            }
        }

//        $where['diy_id']=array('eq',session('diy_id'));
//        $where['title']=array('eq','机构介绍');
//        $page=$model->where($where)->find();
//        $this->assign('page',$page);
        $this->display();
    }
    //线上购卡
    public function card_buy(){
        $type1=D('type1')->select();
        $price=D('price')->find(1);
        $this->assign('price',$price);
        $this->assign('type1',$type1);
        $this->display();
    }

    //购卡
    public function online_buy(){
        $model=D('online_order');

        if(empty(session('out_trade_no'))){
            $data=I('get.');
            //有效天数
            $days=D('days')->find(1);
            $num=$days['num'];
            //生成卡号并绑定信息
            $person_model=D('person');
            $person=$this->getcode();
            $person['type']=0;
            $person['is_use']=0;
            $person['diy_id']=session('diy_id');
            $person['type1_id']=$data['type1_id'];
            $person['type2_id']=$data['type2_id'];
            $person['type3_id']=$data['type3_id'];
            $person['type4_id']=$data['type4_id'];
            $person['type5']=$data['type5'];
            $person['type6']=$data['type6'];
            $person['days']=$num;
            $person_id=$person_model->add($person);

            //设置订单信息
            $priceinfo=D('price')->find(1);
            $insert['price']=$priceinfo['price'];
            $insert['person_id']=$person_id;
            $insert['personinfoid']=session('id');
            $insert['way']='微信';
            $insert['time']=time();
            $insert['diy_id']=session('diy_id');
            $id=$model->add($insert);

            $order_info=$model->find($id);
            $order_info['out_trade_no']=$id.rand(0,99999).time();
            $model->save($order_info);
            $body='购买学习卡，支付金额'.$insert['price'].'元';
            $price=$insert['price']*100;
            $out_trade_no=$order_info['out_trade_no'];
            session('out_trade_no',$out_trade_no);
        }else{
            $where['out_trade_no']=['eq',session('out_trade_no')];
            $order_info=$model->where($where)->find();
            $body='购买学习卡，支付金额'.$order_info['price'].'元';
            $out_trade_no=session('out_trade_no');
            $price=$order_info['price']*100;
            $id=$order_info['id'];
        }


        $jsApiParameters = $this->wxpay($body,$out_trade_no,$price);
        $this->assign('jsApiParameters',$jsApiParameters);
        $this->assign('id',$id);
        $this->display();
    }

    //微信支付
    function wxpay($body,$out_trade_no,$price){
        ini_set('date.timezone','Asia/Shanghai');
        require_once "./wechat/lib/WxPay.Api.php";
        require_once "./wechat/example/WxPay.JsApiPay.php";
        require_once './wechat/example/log.php';
        $tools = new \JsApiPay();
        $openId = $tools->GetOpenid();
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("$body");
        $input->SetAttach("学习卡购买");
        $input->SetOut_trade_no("$out_trade_no");
        $input->SetTotal_fee("$price");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("学习卡购买");
        $input->SetNotify_url("http://hua.safetymf.com/wechat/success.php");

        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);

        $infos=new \WxPayApi();
        $order =$infos->unifiedOrder($input);
        $jsApiParameters = $tools->GetJsApiParameters($order);
        return $jsApiParameters;
    }

    //支付成功
    public function paid(){
        $id=I('get.id');
        $model=D('online_order');
        $person_model=D('person');
        $info=$model->find($id);


        if($info['is_success']==1){

            $info['is_create_code']=1;
            //切换购买工种
            session('personid',$info['person_id']);
            //更新状态

            $wh['id'] = ['eq',$id];
            $res = $model->where($wh)->save($info);
            $data = $person_model->find($info['person_id']);
            if($res) {
                $where['id'] = $info['person_id'];
                $person = $person_model->where($where)->find();
                $person['personinfoid'] = session('id');
                $person['is_use'] = 1;
                $person['active_time'] = time();
                $rre = $person_model->where($where)->save($person);
                if($rre) {
                    session('out_trade_no', null);
                    $this->redirect('Index/index');
                }else{
                    $this->assign('data',$data);
                    $this->display();
                }
            }else{
                $this->assign('data',$data);
                $this->display();
            }
        }else{
            session('out_trade_no',null);
            $this->redirect(U('Index/card_buy'));
        }
    }

    //支付失败(无用)
    public function fail(){
        $id=I('get.id');
        $model=D('online_order');
        $person_model=D('person');
        $info=$model->find($id);
        $fail_id=$info['person_id'];
        $person_model->delete($fail_id);
        $info['person_id']=0;
        $model->save($info);
        $this->redirect(U('card_buy'));
    }

    //生成激活码
    private function getcode(){
        $wh['code']=array('gt',60000000);
        $model=D('person');
        $last=$model->where($wh)->order('code desc')->find();
        if(empty($last)){
            $data['code']=60000001;
        }else{
            $data['code']=$last['code']+1;
        }
        //生成密码
        for ($i=0; $i <6 ; $i++) {
            $data['password'].=rand(0,9);
        }
        return $data;
    }

    public  function sendsms($phone,$code) {
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();
        // 必填，设置短信接收号码
        $request->setPhoneNumbers("$phone");
        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName("安全资格学习卡");
        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode("SMS_123739447");
        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode(Array(  // 短信模板中字段的值
            "code"=>$code['code'],
            "password"=>$code['password'],

        )));
        // var_dump($request);
        // 可选，设置流水号
        // $request->setOutId("yourOutId");
        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        // $request->setSmsUpExtendCode("1234567");
