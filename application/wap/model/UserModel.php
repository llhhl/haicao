<?php
namespace app\wap\model;
use think\Model;
use think\Db;

class UserModel extends Model
{
    protected $name = 'wechat';

    public function getAllWeChat()
    {
        return $this->order('id desc')->select();
    }

   
    public function getWeChatCount($where)
    {
        return $this->where($where)->count();
    }
    
    //添加
    public function insertWeChat($param)
    {
        try{
            $result = $this->allowField(true)->save($param);
            if(false === $result){            
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加推送模板成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    //查找信息
    public function getWeChat($id)
    {
        return $this->where(['openid'=>$id])->find();
    }

    //删除
    public function delWeChat($id)
    {
        try{
            $this->where(['id'=>$id])->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    //修改
    public function editWeChat($param){
        try{
            $result = $this->where(['id'=>$param['id']])->update($param);
            if(false === $result){            
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '修改成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    
}