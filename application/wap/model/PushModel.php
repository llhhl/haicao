<?php
namespace app\wap\model;
use think\Model;
use think\Db;

class PushModel extends Model
{
    protected $name = 'push';

    public function getAllPush()
    {
        return $this->order('id desc')->select();
    }

   
    public function getPushCount($where)
    {
        return $this->where($where)->count();
    }
    
    //添加
    public function insertPush($param)
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
    public function getPush($id)
    {
        return $this->where(['formid'=>$id])->find();
    }

    //删除
    public function delPush($id)
    {
        try{
            $this->where(['id'=>$id])->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    //修改
    public function editPush($param){
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