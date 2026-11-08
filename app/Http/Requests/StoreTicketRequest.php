<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isRequester() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')
                ->where('is_active', true),
            ],

            'subject' => [
                'required',
                'string',
                'min:5',
                'max:150',
            ],

            'description' => [
                'required',
                'string',
                'min:10',
            ],

            'priority' => [
                'sometimes',
                'string',
                Rule::in(['low', 'medium', 'high', 'urgent']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori tiket wajib dipilih.',
            'category_id.exists' => 'Kategori tidak tersedia atau tidak aktif.',

            'subject.required' => 'Subjek tiket wajib diisi.',
            'subject.min' => 'Subjek tiket minimal 5 karakter.',
            'subject.max' => 'Subjek tiket maksimal 150 karakter.',

            'description.required' => 'Deskripsi tiket wajib diisi.',
            'description.min' => 'Deskripsi minimal 10 karakter.',

            'priority.in' => 'Prioritas tiket tidak valid.',
        ];
    }
}
