<?php

namespace App\Http\Controllers\Admin;

use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DistrictsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.districts.index');
    }

    public function data(Request $request)
    {

        $res = District::where('parent_id',$request->get('parent_id',0))->orderBy($request->get('field','sort'),$request->get('order','desc'))->orderBy('id','asc')->paginate($request->get('limit',30))->toArray();
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $res['total'],
            'data'  => $res['data']
        ];
        return response()->json($data);
    }

    /**
     * 更改数据排序
     * @author 悟玄 <roc9574@sina.com>
     * @param Request $request
     * @return void
     */
    public function changeAction(Request $request){
        $id = $request->get('id');
        $field = $request->get('field');
        $value = $request->get('value');
        $module = District::where('id', $id)->first();
        if ($module->update([$field=>$value])) {
            return response()->json(['code'=>0,'msg'=>'更新成功！！！']);
        } else {
            return response()->json(['code'=>1,'msg'=>'更新失败！！！']);
        }
    }

    /**
     * 更新业务模块状态操作[发布、首推、热推]
     * @author 悟玄 <roc9574@sina.com>
     * @param Request $request
     * @return void
     */
    public function statusAction(Request $request){
        $id = $request->get('id');
        $value = $request->get('value');
        $field = $request->get('field');
        if(!in_array($value,[0,1])){
            return response()->json(['code' => 1, 'msg' => '不合法的参数']);
        }
        $_status = District::where('id', $id)->first();


        if (!$_status) {
            return response()->json(['code'=>1,'msg'=>'数据不存在']);
        }
        //是否启用区
        if($_status->level=='district'){
            if ($_status->update([$field=>$value])) {
                return response()->json(['code'=>0,'msg'=>'状态更新成功！！！！']);
            }
        }elseif ($_status->level=='city'){//是否启用市
            $child =  District::where('parent_id',$_status->id)->get(['id'])->toArray();
            $child_arr = array_column($child,'id');
            array_push ($child_arr,$_status->id);
           if(District::whereIn('id',$child_arr)->update([$field=>$value])){
               return response()->json(['code'=>0,'msg'=>'状态更新成功！！！！']);
           }
        }elseif ($_status->level=='province'){//是否启用省
            $dist = District::get(['id','parent_id'])->toArray();
            $arr_id = $_status->id;
            if (empty($dist)){
                return response()->json(['code'=>1,'msg'=>'数据不存在！！！！']);
            }
            $dist_arr = $this->treeDis($dist);
            $id_arr = [];
            array_push($id_arr,$dist_arr[$arr_id]['id']);

            if(isset($dist_arr[$arr_id]['_child'])){
                foreach ($dist_arr[$arr_id]['_child'] as $k=>$val){
                    array_push($id_arr,$val['id']);
                    if(isset($val['_child'])){
                        foreach ($val['_child'] as $i=>$item){
                            array_push($id_arr,$item['id']);
                        }
                    }
                }
            }
            if(District::whereIn('id',$id_arr)->update([$field=>$value])){
                return response()->json(['code'=>0,'msg'=>'状态更新成功！！！！']);
            }
        }

        return response()->json(['code'=>1,'msg'=>'状态更新失败！！！！']);


    }

    /**
     * 处理权限分类
     */
    public function treeDis($list=[], $pk='id', $pid = 'parent_id', $child = '_child', $root = 0)
    {

        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[$data['id']] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}
