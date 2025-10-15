<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Attachment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreAttachmentRequest extends FormRequest
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
            'files.*' => [
                'required',
                'file',
                'max:'.Attachment::MAX_FILE_SIZE,
                'mimes:'.implode(',', Attachment::ALLOWED_EXTENSIONS),
            ],
            'attachable_type' => [
                'required',
                'string',
                Rule::in(['App\\Models\\Ticket', 'App\\Models\\Project', 'App\\Models\\TicketComment']),
            ],
            'attachable_id' => [
                'required',
                'integer',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'files.*' => 'file',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'files.*.max' => 'Each file must not exceed 10MB.',
            'files.*.mimes' => 'Invalid file type. Allowed types: '.implode(', ', Attachment::ALLOWED_EXTENSIONS),
        ];
    }
}
