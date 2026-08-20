<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => 'required|in:paypal,card',
            'shipping_address' => 'required|string|min:10',
            'card_number' => 'required_if:payment_method,card|string|min:16|max:19',
            'card_expiry' => 'required_if:payment_method,card|string|min:5|max:5',
            'card_cvv' => 'required_if:payment_method,card|string|min:3|max:4',
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.required' => 'Seleccione un metodo de pago.',
            'payment_method.in' => 'Metodo de pago no valido.',
            'shipping_address.required' => 'La direccion de envio es obligatoria.',
            'shipping_address.min' => 'La direccion debe tener al menos 10 caracteres.',
            'card_number.required_if' => 'El numero de tarjeta es obligatorio.',
            'card_number.min' => 'El numero de tarjeta debe tener al menos 16 digitos.',
            'card_expiry.required_if' => 'La fecha de expiracion es obligatoria.',
            'card_cvv.required_if' => 'El CVV es obligatorio.',
        ];
    }
}
