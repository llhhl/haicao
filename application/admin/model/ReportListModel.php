<?php
// +----------------------------------------------------------------------
// | cms_api [ iActing ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018~2088 https://www.ijiandian.com All rights reserved.
// +----------------------------------------------------------------------
// | Data: 2020/5/18 0018 17:47
// +----------------------------------------------------------------------
// | Author: iActing <758246061@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\model;


use think\Model;

class ReportListModel extends Model
{
    protected $name = 'report_list';
    public function getCreateTimeAttr($value)
    {
        return  $value;
    }


    public function getUserIdAttr($value)
    {
        $info = ReportUserModel::where('id',$value)->find();
        return [
            'id'=>$info['id'],
            'real_name'=>$info['real_name']
        ];
    }

    /**
     * 根据搜索条件获取报表列表信息
     */
    public function getUsersByWhere($map,$job_number,$house_address,$client_mobile,$start_time,$end_time, $Nowpage, $limits)
    {
        $where=$map;
        if(!empty($map['job_number'])){
            $where['job_number'] = $job_number;
        }

        if(!empty($map['client_mobile'])){
            $where['client_mobile'] = $client_mobile;
        }

        if(!empty($start_time) && !empty($end_time)){
            $where['start_time'] = $start_time;
            $where['end_time'] = $end_time;
        }

        if(!empty($map['house_address'])){
            $where['house_address'] = $house_address;
        }

        return $this->where($map)->page($Nowpage, $limits)->order('id desc')->paginate(10,false,['query'=>$where]);




    }

    public function delUser($id)
    {
        try{
            $this->where('id', $id)->delete();
            writelog(session('admin_uid'),session('username'),'用户【'.session('admin_username').'】删除报表成功(ID='.$id.')',1);
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}