<?php

namespace App\Http\Controllers\Admin;

use App\Models\Member;
use App\Models\Message;
use App\Models\User;
use App\Traits\PushMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    use PushMessage;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.message.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $model = new Message();
        $res = $request->only(['start_time','end_time','title']);
        if (!empty($res)){
            if ($res['title']){
                $model = $model->where('title','like','%'.$res['title'].'%');
            }
            if ($res['start_time'] && !$res['end_time']){
                $model = $model->where('created_at','>=',$res['start_time']);
            }elseif (!$res['start_time'] && $res['end_time']){
                $model = $model->where('created_at','<=',$res['end_time']);
            }elseif ($res['start_time'] && $res['end_time']){
                $model = $model->whereBetween('created_at',[$res['start_time'],$res['end_time']]);
            }
        }
        $res = $model->orderBy('read','asc')->orderBy('id','desc')->paginate($request->get('limit',30))->toArray();
        $users = User::pluck('name','uuid');
        $member = Member::pluck('name','uuid');
        foreach ($res['data'] as &$d) {
            switch ($d['flag']){
                case 12:
                    $send_name = '系统';
                    $accept_name = $users[$d['accept_uuid']]??'用户不存在';
                    break;
                case 13:
                    $send_name = '系统';
                    $accept_name = $member[$d['accept_uuid']]??'用户不存在';
                    break;
                case 22:
                    $send_name = $users[$d['send_uuid']]??'用户不存在';
                    $accept_name = $users[$d['accept_uuid']]??'用户不存在';
                    break;
                case 23:
                    $send_name = $users[$d['send_uuid']]??'用户不存在';
                    $accept_name = $member[$d['accept_uuid']]??'用户不存在';
                    break;
                case 32:
                    $send_name = $member[$d['send_uuid']]??'用户不存在';
                    $accept_name = $users[$d['accept_uuid']]??'用户不存在';
                    break;
                case 33:
                    $send_name = $member[$d['send_uuid']]??'用户不存在';
                    $accept_name = $member[$d['accept_uuid']]??'用户不存在';
                    break;
                default:
                    $send_name = '用户不存在';
                    $accept_name = '用户不存在';
                    break;
            };
            $d['send_name'] = $send_name;
            $d['accept_name'] = $accept_name;
        }
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $res['total'],
            'data'  => $res['data']
        ];
        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function getUser(Request $request)
    {
        if ($request->ajax()){
            //默认后台用户
            $model = new User();
            if ($request->get('user_type')==3){
                $model = new Member();
            }
            $keywords = $request->get('keywords');
            if ($keywords){
                $model = $model->where('name','like','%'.$keywords.'%')->orWhere('phone','like','%'.$keywords.'%');
            }
            $res = $model->orderBy('id','desc')->paginate($request->get('limit',30))->toArray();
            $data = [
                'code' => 0,
                'msg'   => '正在请求中...',
                'count' => $res['total'],
                'data'  => $res['data']
            ];
            return response()->json($data);
        }
        return view('admin.message.getUser');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.message.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required|min:4|max:200',
            'content' => 'required|min:4|max:400'
        ]);
        if (empty($request->get('user'))){
            return back()->withInput()->with(['status'=>'请选择用户']);
        }
        $data = $request->only(['title','content','user']);
        //后台推送给后台用户
        if (isset($data['user'][2]) && !empty($data['user'][2])){
            foreach ($data['user'][2] as $uuid){
                $d = [
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'send_uuid' => auth()->user()->uuid,
                    'accept_uuid' => $uuid,
                    'flag' => 22
                ];
                $this->push($d);
            }
        }
        //后台推送给前台用户
        if (isset($data['user'][3]) && !empty($data['user'][3])){
            foreach ($data['user'][3] as $uuid){
                $d = [
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'send_uuid' => auth()->user()->uuid,
                    'accept_uuid' => $uuid,
                    'flag' => 23
                ];
                $this->push($d);
            }
        }
        return back()->with(['status'=>'消息推送完成']);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (empty($ids)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }
        if (Message::destroy($ids)){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);
    }

    public function mine(Request $request)
    {
        if ($request->ajax()){
            $res = Message::where('accept_uuid',auth()->user()->uuid)->orderBy('read','asc')->orderBy('id','desc')->paginate($request->get('limit',30))->toArray();
            $users = User::pluck('name','uuid');
            $member = Member::pluck('name','uuid');
            foreach ($res['data'] as &$d) {
                switch ($d['flag']){
                    case 12:
                        $send_name = '系统';
                        $accept_name = $users[$d['accept_uuid']]??'用户不存在';
                        break;
                    case 13:
                        $send_name = '系统';
                        $accept_name = $member[$d['accept_uuid']]??'用户不存在';
                        break;
                    case 22:
                        $send_name = $users[$d['send_uuid']]??'用户不存在';
                        $accept_name = $users[$d['accept_uuid']]??'用户不存在';
                        break;
                    case 23:
                        $send_name = $users[$d['send_uuid']]??'用户不存在';
                        $accept_name = $member[$d['accept_uuid']]??'用户不存在';
                        break;
                    case 32:
                        $send_name = $member[$d['send_uuid']]??'用户不存在';
                        $accept_name = $users[$d['accept_uuid']]??'用户不存在';
                        break;
                    case 33:
                        $send_name = $member[$d['send_uuid']]??'用户不存在';
                        $accept_name = $member[$d['accept_uuid']]??'用户不存在';
                        break;
                    default:
                        $send_name = '用户不存在';
                        $accept_name = '用户不存在';
                        break;
                };
                $d['send_name'] = $send_name;
                $d['accept_name'] = $accept_name;
            }
            $data = [
                'code' => 0,
                'msg'   => '正在请求中...',
                'count' => $res['total'],
                'data'  => $res['data']
            ];
            return response()->json($data);
        }
        return view('admin.message.mine');
    }

    public function read($id)
    {
        $message = Message::where('accept_uuid',auth()->user()->uuid)->find($id);
        if (!$message){
            return response()->json(['code'=>1,'msg'=>'消息不存在']);
        }
        if ($message->update(['read'=>2])){
            return response()->json(['code'=>0,'msg'=>'状态已更新']);
        }
        return response()->json(['code'=>1,'msg'=>'系统错误']);
    }

}
