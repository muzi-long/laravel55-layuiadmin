<?php

namespace App\Http\Controllers\Admin;

use App\Models\ConfigGroup;
use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use DB;

class ConfigurationController extends Controller
{
    /**
     * 配置主页
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $groups = ConfigGroup::with('configurations')->orderBy('sort','asc')->get();
        return View::make('admin.configuration.index',compact('groups'));
    }

    /**
     * 添加配置
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $groups = ConfigGroup::orderBy('sort','asc')->get();
        return View::make('admin.configuration.create',compact('groups'));
    }

    /**
     * 添加配置
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->all(['group_id','label','key','val','type','tips','sort']);
        try{
            Configuration::create($data);

        }catch (\Exception $exception){
            return Redirect::back()->withErrors('添加失败');
        }
        //缓存配置信息
        $configuration = Configuration::pluck('val','key');
        $request->session()->put('configuration',$configuration);
        return Redirect::to(URL::route('admin.configuration'))->with(['success'=>'添加成功']);
    }

    /**
     * 更新配置
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $data = $request->except(['_token','id']);
        DB::beginTransaction();
        try{
            foreach ($data as $k => $v){
                DB::table('configuration')->where('key',$k)->update(['val'=>$v]);
            }
            DB::commit();
        }catch (\Exception $exception){
            DB::rollback();
            return Response::json(['code'=>1,'msg'=>'更新失败']);
        }
        //缓存配置信息
        $configuration = Configuration::pluck('val','key');
        $request->session()->put('configuration',$configuration);
        return Response::json(['code'=>0,'msg'=>'更新成功']);
    }

}
