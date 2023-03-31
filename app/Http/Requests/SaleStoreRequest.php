<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'client' =>  ['string', 'max:255', 'nullable'],
            'item.*.product' => ['string', 'max:255', 'required'],
            'item.*.price' => ['decimal:0,2', 'min:0', 'required'],
            'payment_method' => ['in:credit-card,debit-card,pix,boleto', 'required'],
            'installment.*.date' => ['date_format:Y-m-d'],
            'installment.*.value' => ['decimal:0,2', 'min:0', 'required'],
            'installment.*.observations' => ['string', 'max:255', 'nullable'],
        ];
    }
}
