<?php
/**
 * Created by 顶呱呱.
 * User: 李帅
 * Date: 2019/3/12
 * Time: 9:49
 */
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteRequest extends FormRequest
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
            'site_name' => 'chinese:20',
            'title' => 'chinese:20',
            'keywords' => 'chinese:200',
            'description' => 'chinese:200',
            'copyright' => 'chinese:50',
            'phone' => 'chinese:20',
            'img_type' => 'chinese:50',
            'img_size' => 'numeric|max:10',
            'count_code' => 'chinese:200',

        ];
    }

    public function messages()
    {
        return [
            'site_name.chinese' => '网站名称不能超过20个字',
            'title.chinese' => '站点标题不能超过20个字',
            'keywords.chinese' => '站点关键词不能超过200个字',
            'description.chinese' => '站点描述不能超过200个字',
            'copyright.chinese' => 'CopyRight不能超过50个字',
            'phone.chinese' => '电话不能超过20个字',
            'img_type.chinese' => '附件类型不能超过50个字',
            'img_size.numeric' => '附件大小必须是数字',
            'img_size.max' => '附件大小不能超过10M',
            'count_code.chinese' => '统计代码不能超过200个字',


        ];
    }
}