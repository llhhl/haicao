<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class MpTempModel extends Model
{
    protected $name = 'mptemp';

    public function getAllMpTemp()
    {
        return $this->order('id desc')->select();
    }

   
    public function getMpTempCount($where)
    {
        return $this->where($where)->count();
    }
    
    //添加
    public function insertMpTemp($param)
    {
		if(!empty($param['id'])){
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
	else{
			try{
				$result = $this->allowField(true)->save($param);
				if(false === $result){            
					return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
				}else{
					return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
				}
			}catch( PDOException $e){
				return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
			}
		}
    }

    //查找信息
    public function getMpTemp($id)
    {
        return $this->where(['id'=>$id])->find();
    }

    //删除
    public function delMpTemp($id)
    {
        try{
            $this->where(['id'=>$id])->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    
    
}