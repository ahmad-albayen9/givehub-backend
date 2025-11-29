<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCharityProfileRequest extends FormRequest
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
        // استخدام قاعدة 'sometimes' لضمان التحقق من الحقل فقط إذا كان موجودًا في الطلب
    return [
        'name' => ['sometimes', 'required', 'string', 'max:255'],
        'license_number' => ['sometimes', 'required', 'string', 'unique:charities_profile,license_number,' . $this->route('charity')],
        'city_id' => ['sometimes', 'required', 'integer', 'exists:cities,id'],
        'description' => ['sometimes', 'required', 'string'],
        // يمكنك إضافة حقول أخرى هنا مثل 'address', 'phone', إلخ.
    ];
    }
}
