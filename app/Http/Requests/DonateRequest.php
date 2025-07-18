<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonateRequest extends FormRequest
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
    
            'campaign_id' => 'required|exists:crowdfunding_campaigns,id',
            'amount' => 'required|numeric|min:1',
        ];
    }

    public function message(): array
    {
         return [
            'amount.required' => 'Jumlah donasi wajib diisi.',
            'amount.numeric' => 'Jumlah donasi harus berupa angka.',
            'amount.min' => 'Jumlah donasi minimal Rp1.',
            'campaign_id.required' => 'Campaign ID wajib diisi.',
            'campaign_id.exists' => 'Campaign tidak ditemukan.',
        ];
    }
}