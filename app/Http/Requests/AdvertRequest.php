<?php
/**
 * Created by 顶呱呱.
 * User: 李帅
 * Date: 2019/3/1
 * Time: 14:03
 */
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'position_id'   => 'required',
            'title' => 'required|chinese:20',
            'description'   => 'chinese:200',
            'thumb' => 'required',
          //  'link' => 'url',
        ];
    }

    public function messages(){
        return [
            'position_id.required' => '请选择分类',
            'title.required' => '请输入标题',
            'title.chinese' => '标题限制 20 个字符',
            'thumb.required' => '请上传缩略图',
            //'link.url' => '请输入合法url',
            'description.chinese' => '描述限制 200 个字',

        ];
    }
}
