<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class ReportUserModel extends Model
{
    protected $name = 'report_user';

    /**
     * 根据搜索条件获取用户列表信息
     */
    public function getUsersByWhere($map, $Nowpage, $limits)
    {
        return $this->where($map)->page($Nowpage, $limits)->order('id desc')->paginate(10);
    }

    public function getAllUsers($where)
    {
        return $this->where($where)->count();
    }

    public function insertUser($param)
    {
        $param['status'] = array_key_exists("status", $param) ? 1 : 0;
        $param['download'] = array_key_exists("download", $param) ? 1 : 0;
        try{
            $result = $this->validate('UserValidate')->allowField(true)->save($param);
            if(false === $result){            
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                //$userid = $this->getLastInsID();
                writelog(session('admin_uid'),$param['mobile'],'用户【'.$param['mobile'].'】编辑成功',1);
                return ['code' => 1, 'data' => '', 'msg' => '添加用户成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function editUser($param)
    {
        $param['status'] = array_key_exists("status", $param) ? 1 : 0;
        $param['download'] = array_key_exists("download", $param) ? 1 : 0;
        try{
            $result =  $this->validate('UserValidate')->allowField(true)->save($param, ['id' => $param['id']]);
            if(false === $result){            
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                writelog(session('admin_uid'),$param['mobile'],'用户【'.$param['mobile'].'】编辑成功',1);
                return ['code' => 1, 'data' => '', 'msg' => '编辑用户成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function getOneUser($id)
    {
        return $this->where(['id'=>$id])->find();
    }

    public function delUser($id)
    {
        try{
            $this->where('id', $id)->delete();
            Db::name('auth_group_access')->where(['uid'=>$id])->delete();
            writelog(session('admin_uid'),session('username'),'用户【'.session('admin_username').'】删除用户成功(ID='.$id.')',1);
            return ['code' => 1, 'data' => '', 'msg' => '删除用户成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}