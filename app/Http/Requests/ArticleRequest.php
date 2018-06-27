<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
            'category_id'   => 'required|numeric',
            'title' => 'required|string|max:200|min:4',
            'keywords'  => 'required|string',
            'description'   => 'required|string',
            'content'   => 'required|string',
            //'thumb' => 'required|string'
        ];
    }
}
