<?php
// +---------------------------------------------------------------------+
// | MamiTianshi    | [ CREATE BY WF_RT TEAM ]                           |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Fjwcoder <fjwcoder@gmail.com>                          |
// +---------------------------------------------------------------------+
// | Repository | git@github.com:fjwcoder/mamitianshi_server.git         |
// +---------------------------------------------------------------------+

namespace app\index\controller;

use think\Db;
/**
 * 前端首页控制器
 * create by fjw in 19.9.28
 */
class Index extends IndexBase
{

    /**
     * 
     */
    public function index(){

        $setting = Db::name('settings') -> where(['status'=>1]) -> column('Value', 'Name');
        // dump($config); die;
        $show_record_num = 10; // 默认显示行数
        $view_path = '';
        switch($setting['App.ScreenType']){
            case '1001': 
                $show_record_num = $setting['App.WritingDeskScreenRows']; 
                $view_path = 'index';
            break;
            case '1002': 
                $show_record_num = $setting['App.VaccinationDeskScreenRows'];  
                $view_path = 'index';
            break;
            case '1003': 
                $video_ext = ['mp4', 'WebM', 'Ogg'];
                $show_record_num = $setting['App.ObseverRoomScreenRows']; 
                $view_path = 'liuguan';
                // 读取视频文件
                $video_path = $setting['App.ObseverRoomVideoPath'];//'/Volumes/QQ404678606/vsfile/';
                $video_list = [];
                if(is_dir($video_path)){

                    $file_list = scandir($video_path);
                    foreach($file_list as $k=>$v){
                        $v_file = $video_path.$v;
                        $ext = pathinfo($v)['extension'];
                        if(is_file($v_file) && in_array($ext, $video_ext)){
                            $video_list[] = $video_path.$v;
                        }
                        
                    }
// dump($video_list);die;
                    $this->assign('video_list', json_encode($video_list, 320));


                }else{
                    dump('视频库不存在'); die;
                    
                }
                
             break;
        }

        $config = [
            'host'=>$setting['App.QueueServerAddress'],
            'uid'=>$setting['App.ScreenType'],
            'show_record_num'=>$show_record_num
        ];

        $this->assign('config', json_encode($config, 320));


        return $this->fetch($view_path);
    }





}
