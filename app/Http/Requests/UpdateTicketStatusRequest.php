<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && ($user->isAdmin() || $user->isSupport());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status'=> [
                'required',
                'string',
                Rule::in(['open', 'in_progress','pending','resolved', 'closed']),
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000'
            ]
        ];
    }

    public function message(): array
    {
        return [
            'status.required' => 'Status is required',
            'status.in' => 'Status is invalid',
            'description.max' => 'description cannot exceed 1000 characters',
        ];
    }
}
