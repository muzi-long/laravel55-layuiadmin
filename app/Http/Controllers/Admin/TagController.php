<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TagRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    /**
     * 标签列表
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return View::make('admin.tag.index');
    }

    /**
     * 标签数据接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {

        $res = Tag::orderBy('sort','asc')->orderBy('id','desc')->paginate($request->get('limit',30));
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $res->total(),
            'data'  => $res->items(),
        ];
        return Response::json($data);
    }

    /**
     * 添加标签
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return View::make('admin.tag.create');
    }

    /**
     * 添加标签
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(TagRequest $request)
    {
        $data = $request->all(['name','sort']);
        try{
            Tag::create($data);
            return Redirect::to(URL::route('admin.tag'))->with(['success'=>'更新成功']);
        }catch (\Exception $exception){
            return Redirect::back()->withErrors('添加失败');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * 更新标签
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        return View::make('admin.tag.edit',compact('tag'));
    }

    /**
     * 更新标签
     * @param TagRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TagRequest $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $data = $request->all(['name','sort']);
        try{
            $tag->update($data);
            return Redirect::to(URL::route('admin.tag'))->with(['success'=>'更新成功']);
        }catch (\Exception $exception){
            return Redirect::back()->withErrors('更新失败');
        }
    }

    /**
     * 删除标签
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (!is_array($ids) || empty($ids)){
            return Response::json(['code'=>1,'msg'=>'请选择删除项']);
        }
        DB::beginTransaction();
        try{
            //删除中间表article_tag
            DB::table('article_tag')->whereIn('tag_id',$ids)->delete();
            //删除主表tag
            DB::table('tags')->whereIn('id',$ids)->delete();
            DB::commit();
            return Response::json(['code'=>0,'msg'=>'删除成功']);
        }catch (\Exception $exception){
            DB::rollback();
            return Response::json(['code'=>1,'msg'=>'删除失败','data'=>$exception->getMessage()]);
        }
    }
}
