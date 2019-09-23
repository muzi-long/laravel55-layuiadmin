<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * 资讯列表
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $categories = Category::with('allChilds')->where('parent_id',0)->orderBy('sort','asc')->get();
        return View::make('admin.article.index',compact('categories'));
    }

    /**
     * 资讯数据接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $model = Article::query();
        if ($request->get('category_id')){
            $model = $model->where('category_id',$request->get('category_id'));
        }
        if ($request->get('title')){
            $model = $model->where('title','like','%'.$request->get('title').'%');
        }
        $res = $model->with(['tags','category'])->orderBy('id','desc')->paginate($request->get('limit',30));
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $res->total(),
            'data'  => $res->items(),
        ];
        return Response::json($data);
    }

    /**
     * 添加资讯
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        //分类
        $categories = Category::with('allChilds')->where('parent_id',0)->orderBy('sort','desc')->get();
        //标签
        $tags = Tag::get();
        return View::make('admin.article.create',compact('tags','categories'));
    }

    /**
     * 添加资讯
     * @param ArticleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ArticleRequest $request)
    {
        $data = $request->all();
        try{
            $article = Article::create($data);
            $article->tags()->sync($request->get('tags',[]));
            return Redirect::to(URL::route('admin.article'))->with(['success'=>'添加成功']);
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
     * 更新资讯
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $article = Article::with('tags')->findOrFail($id);
        //分类
        $categories = Category::with('allChilds')->where('parent_id',0)->orderBy('sort','asc')->get();
        //标签
        $tags = Tag::get();
        foreach ($tags as $tag){
            $tag->checked = $article->tags->contains($tag) ? 'checked' : '';
        }
        return View::make('admin.article.edit',compact('article','categories','tags'));
    }

    /**
     * 更新资讯
     * @param ArticleRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ArticleRequest $request, $id)
    {
        $article = Article::with('tags')->findOrFail($id);
        $data = $request->all();
        try{
            $article->update($data);
            $article->tags()->sync($request->get('tags',[]));
            return Redirect::to(URL::route('admin.article'))->with(['success'=>'更新成功']);
        }catch (\Exception $exception){
            return Redirect::back()->withErrors('更新失败');
        }
    }

    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (!is_array($ids) || empty($ids)){
            return Response::json(['code'=>1,'msg'=>'请选择删除项']);
        }
        DB::beginTransaction();
        try{
            //删除中间表article_tag
            DB::table('article_tag')->whereIn('article_id',$ids)->delete();
            //删除主表tag
            DB::table('articles')->whereIn('id',$ids)->delete();
            DB::commit();
            return Response::json(['code'=>0,'msg'=>'删除成功']);
        }catch (\Exception $exception){
            DB::rollback();
            return Response::json(['code'=>1,'msg'=>'删除失败','data'=>$exception->getMessage()]);
        }
    }
}
