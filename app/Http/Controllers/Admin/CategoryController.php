<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class CategoryController extends Controller
{
    /**
     * 分类列表
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return View::make('admin.category.index');
    }

    /**
     * 分类列表数据接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $res = Category::get();
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $res->count(),
            'data'  => $res
        ];
        return Response::json($data);
    }

    /**
     * 添加分类
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $categories = Category::with('allChilds')->where('parent_id',0)->orderBy('sort','asc')->orderBy('id','desc')->get();
        return View::make('admin.category.create',compact('categories'));
    }

    /**
     * 添加分类
     * @param CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->all(['name','sort','parent_id']);
        try{
            Category::create($data);
            return Redirect::to(URL::route('admin.category'))->with(['success'=>'添加成功']);
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
     * 更新分类
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::with('allChilds')->where('parent_id',0)->orderBy('sort','asc')->orderBy('id','desc')->get();
        return View::make('admin.category.edit',compact('category','categories'));
    }

    /**
     * 更新分类
     * @param CategoryRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->all(['name','sort','parent_id']);
        try{
            $category->update($data);
            return Redirect::to(URL::route('admin.category'))->with(['success'=>'更新成功']);
        }catch (\Exception $exception){
            return Redirect::back()->withErrors('更新失败');
        }
    }

    /**
     * 删除分类
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        $category = Category::with(['childs','articles'])->find($ids[0]);
        if ($category == null){
            return Response::json(['code'=>1,'msg'=>'分类不存在']);
        }
        if ($category->childs->isNotEmpty()){
            return Response::json(['code'=>1,'msg'=>'该分类下有子分类，不能删除']);
        }
        if ($category->articles->isNotEmpty()){
            return Response::json(['code'=>1,'msg'=>'该分类下有文章，不能删除']);
        }
        try{
            $category->delete();
            return Response::json(['code'=>0,'msg'=>'删除成功']);
        }catch (\Exception $exception){
            return Response::json(['code'=>1,'msg'=>'删除失败']);
        }
    }
}
