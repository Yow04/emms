<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AssignTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'assigned_to' => [
                'required',
                'integer',
                Rule::exists('users', 'id')
                ->where('role', 'support'),
            ],

            'description' =>[
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function message(): array
    {
        return[
            'assigned_to.required' => 'Staff wajib dipilih',
            'assigned_to.exists' => 'Akun yang dipilih merupakan non staff support',
            'description.max' => 'deskripsi maksimal 1000 karakter',
        ];
    }
}
