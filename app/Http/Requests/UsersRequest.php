<?php

namespace App\Http\Requests;

use Bouncer;
use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class UsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $results = false;

        if(backpack_auth()->check())
        {
            if(Bouncer::is(backpack_user())->a('god', 'master', 'admin'))
            {
                $results = true;
            }
        }
        // only allow updates if the user is logged in
        // @todo - the only users allowed gods and masters
        return $results;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'name' => 'required|min:5|max:255'
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [

        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
