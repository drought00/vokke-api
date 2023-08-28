<?php

namespace App\Http\Requests;

use App\Enumerators\KangarooEnumerator;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class KangarooRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('kangaroos', 'name')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })->ignore($this->kangaroo),
            ],
            'nickname' => 'nullable|string',
            'weight' => 'required|numeric|min:0',
            'height' => 'required|numeric|min:0',
            'gender' => 'required|in:' . implode(',', KangarooEnumerator::GENDERS),
            'color' => 'nullable|string',
            'friendliness' => 'required|in:' . implode(',', KangarooEnumerator::FRIENDLINESS),
            'birthday' => 'required|date|before_or_equal:today'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = [
            'success' => false,
            'message' => 'The given data was invalid.',
            'errors' => $validator->errors(),
        ];

        throw response()->json($response, 422);
    }
}
