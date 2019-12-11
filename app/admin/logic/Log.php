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

namespace app\admin\logic;

/**
 * 行为日志逻辑
 */
class Log extends AdminBase
{
    
    /**
     * 获取日志列表
     */
    public function getLogList()
    {
        
        $sub_member_ids = $this->logicMember->getSubMemberIds(MEMBER_ID);
        
        $where = [];
        
        $sub_member_ids[] = MEMBER_ID;
        
        !IS_ROOT && $where['member_id'] = ['in', $sub_member_ids];
        
        return $this->modelAdminActionLog->getList($where, true, 'create_time desc');
    }
  
    /**
     * 日志删除
     */
    public function logDel($where = [])
    {
        
        return $this->modelAdminActionLog->deleteInfo($where) ? [RESULT_SUCCESS, '日志删除成功'] : [RESULT_ERROR, $this->modelAdminActionLog->getError()];
    }
    
    /**
     * 日志添加
     */
    public function logAdd($name = '', $describe = '')
    {
        
        $member_info = session('member_info');
        
        $request = request();
        
        $data['member_id'] = $member_info['id'];
        $data['username']  = $member_info['UserName'];
        $data['ip']        = $request->ip();
        $data['url']       = $request->url();
        $data['status']    = DATA_NORMAL;
        $data['name']      = $name;
        $data['describe']  = $describe;
        
        $url = url('logList');
        
        $this->modelAdminActionLog->is_update_cache_version = false;
        
        return $this->modelAdminActionLog->setInfo($data) ? [RESULT_SUCCESS, '日志添加成功', $url] : [RESULT_ERROR, $this->modelAdminActionLog->getError()];
    }
}
