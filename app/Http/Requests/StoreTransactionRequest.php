<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount'   => 'required|numeric|min:0',
            'document' => 'required|string|max:255',
            'currency' => 'required|string|max:3',
            'status'   => 'nullable|string|in:' . implode(',', array_column(\App\Enums\TransactionStatus::cases(), 'value')),
        ];
    }
}
