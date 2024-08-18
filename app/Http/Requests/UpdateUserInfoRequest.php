<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserInfoRequest extends FormRequest {
    /**
    * Determine if the user is authorized to make this request.
    */

    public function authorize(): bool {
        return true;
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
    */

    public function rules(): array {
        // dd(12);
        return [
            // 'id' => 'required|exists:users,id',
            'voter_candidate_id'=>"int",
            'source'=>'string',
            // 'votting_year'=>'numeric|digits:4',
        ];
    }
}
