<?php

namespace App\Http\Requests\Guests;

use Illuminate\Foundation\Http\FormRequest;

class RsvpStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'guest.name' => ['required', 'string', 'max:255'],
            'guest.whatsapp' => ['required', 'string', 'max:30'],
            'guest.email' => ['nullable', 'email', 'max:255'],
            'attending' => ['required', 'boolean'],
            'guests_count' => ['required_if:attending,true', 'nullable', 'integer', 'min:1', 'max:20'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'guest.name' => 'nome',
            'guest.whatsapp' => 'WhatsApp',
            'guest.email' => 'e-mail',
            'attending' => 'confirmação',
            'guests_count' => 'quantidade de pessoas',
        ];
    }
}
