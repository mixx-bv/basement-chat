<?php

declare(strict_types=1);

namespace BasementChat\Basement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePrivateMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard(config('basement.guard'))->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,array<mixed>>
     */
    public function rules(): array
    {
        return [
            'value' => ['required', 'max:255', 'string'],
        ];
    }
}
