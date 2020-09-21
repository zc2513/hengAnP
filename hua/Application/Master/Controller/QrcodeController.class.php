<?php
namespace Master\Controller;
use Think\Controller;
header('Access-Control-Allow-Origin:*'); // *代表允许任何网址请求
header("Content-type: text/html; charset=utf-8");
Vendor('phpqrcode.phpqrcode');
/**
 * 个人中心控制器
 * @package Home\Controller
 */
class QrcodeController extends Controller {
    public function index(){
        $id = I('managerid');
        $classid = I('classid');
        $url="http://w.safetymf.com/index.php/Wechat/wechat/getcode/id/".$id ."/classid/".$classid;
        $level=3;
        $size=4;
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        //生成二维码图片
        ob_clean();
        $object = new \QRcode();
        echo $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);
//        $this -> ajaxReturn(array('qrcode'=>$object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2),'code'=>200 ));
      }











}