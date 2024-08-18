<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCandidateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'dob' => 'required|date',
            'birth_place' => 'required|string',
            'occupation' => 'required|string',
            'position' => 'required|string',
            'voter_party_id' => 'required|numeric',
            'candidate_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max size as needed
        ];
    }
}
