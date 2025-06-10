<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
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
        $contactId = $this->contact->id;
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:contacts,email,' . $contactId,
            'phone' => 'sometimes|required|string|max:20',
        ];
    }
}
