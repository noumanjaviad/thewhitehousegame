<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SelectPartyLeadersRequest extends FormRequest
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
            'votter_party_id' => 'required|exists:votter_parties,id',
            'president_id' => 'required|exists:voter_candidates,id',
            'vice_president_id' => 'required|exists:voter_candidates,id',
        ];
    }
}
