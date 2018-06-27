<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberUpdateRequest extends FormRequest
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
        $return =  [
            'name'  => 'required|min:2',
            'phone'   => 'required|numeric|regex:/^1[3456789][0-9]{9}$/|unique:members,phone,'.$this->get('id').',id',
        ];
        if ($this->get('password') || $this->get('password_confirmation')){
            $return['password'] = 'required|confirmed|min:6|max:14';
        }
        return $return;
    }
}
