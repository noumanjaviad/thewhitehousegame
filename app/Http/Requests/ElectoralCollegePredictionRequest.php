<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ElectoralCollegePredictionRequest extends FormRequest
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
            'state_predictions' => 'required|array',
            'state_predictions.*.state_id' => 'required|exists:user_states,id',
            'state_predictions.*.party_id' => 'required|exists:votter_parties,id',
        ];
    }
}
