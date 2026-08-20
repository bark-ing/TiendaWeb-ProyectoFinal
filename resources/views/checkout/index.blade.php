<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Checkout
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <form action="{{ route('checkout.procesar') }}" method="POST" id="checkout-form">
                        @csrf

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Direccion de Envio</h3>
                            <div>
                                <label for="direccion_envio" class="block text-sm font-medium text-gray-700 mb-1">Direccion completa</label>
                                <textarea
                                    name="direccion_envio"
                                    id="direccion_envio"
                                    rows="3"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                    placeholder="Provincia, canton, distrito, direccion exacta..."
                                    required
                                >{{ old('direccion_envio') }}</textarea>
                                @error('direccion_envio')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Metodo de Pago</h3>

                            <div class="space-y-4">
                                <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:border-purple-500 transition {{ old('metodo_pago') === 'paypal' ? 'border-purple-500 bg-purple-50' : 'border-gray-200' }}">
                                    <input type="radio" name="metodo_pago" value="paypal" class="text-purple-600 focus:ring-purple-500" {{ old('metodo_pago') === 'paypal' ? 'checked' : '' }}>
                                    <span class="ml-3">
                                        <span class="font-medium text-gray-800">PayPal</span>
                                        <span class="block text-sm text-gray-500">Paga con tu cuenta de PayPal</span>
                                    </span>
                                </label>

                                <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:border-purple-500 transition {{ old('metodo_pago') === 'card' ? 'border-purple-500 bg-purple-50' : 'border-gray-200' }}">
                                    <input type="radio" name="metodo_pago" value="card" class="text-purple-600 focus:ring-purple-500" {{ old('metodo_pago') === 'card' ? 'checked' : '' }}>
                                    <span class="ml-3">
                                        <span class="font-medium text-gray-800">Tarjeta (Simulado)</span>
                                        <span class="block text-sm text-gray-500">Simulacion de pago con tarjeta</span>
                                    </span>
                                </label>
                            </div>
                            @error('metodo_pago')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            <div id="card-fields" class="mt-4 space-y-4 {{ old('metodo_pago') !== 'card' ? 'hidden' : '' }}">
                                <div>
                                    <label for="card_number" class="block text-sm font-medium text-gray-700 mb-1">Numero de Tarjeta</label>
                                    <input
                                        type="text"
                                        name="card_number"
                                        id="card_number"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                        placeholder="1234 5678 9012 3456"
                                        maxlength="19"
                                        value="{{ old('card_number') }}"
                                    >
                                    @error('card_number')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="card_expiry" class="block text-sm font-medium text-gray-700 mb-1">Fecha Expiracion</label>
                                        <input
                                            type="text"
                                            name="card_expiry"
                                            id="card_expiry"
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                            placeholder="MM/AA"
                                            maxlength="5"
                                            value="{{ old('card_expiry') }}"
                                        >
                                        @error('card_expiry')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="card_cvv" class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                        <input
                                            type="text"
                                            name="card_cvv"
                                            id="card_cvv"
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                            placeholder="123"
                                            maxlength="4"
                                            value="{{ old('card_cvv') }}"
                                        >
                                        @error('card_cvv')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-purple-600 text-white py-3 px-6 rounded-lg hover:bg-purple-700 transition font-semibold text-lg">
                            Confirmar Compra
                        </button>
                    </form>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Resumen del Pedido</h3>

                        <div class="space-y-3 mb-4">
                            @foreach($carrito as $item)
                                <div class="flex justify-between items-center text-sm">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">{{ $item['nombre'] }}</p>
                                        <p class="text-gray-500">Cant: {{ $item['cantidad'] }} @if($item['talla'])| Talla: {{ $item['talla'] }} @endif @if($item['color'])| {{ $item['color'] }} @endif</p>
                                    </div>
                                    <span class="font-medium">₡{{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <hr class="my-4">

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span>₡{{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">IVA (13%)</span>
                                <span>₡{{ number_format($impuesto, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Envio</span>
                                <span>{{ $envio > 0 ? '₡' . number_format($envio, 0, ',', '.') : 'Gratis' }}</span>
                            </div>
                            <hr class="my-2">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span class="text-purple-600">₡{{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentRadios = document.querySelectorAll('input[name="metodo_pago"]');
            const cardFields = document.getElementById('card-fields');

            paymentRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'card') {
                        cardFields.classList.remove('hidden');
                    } else {
                        cardFields.classList.add('hidden');
                    }
                });
            });

            const cardNumber = document.getElementById('card_number');
            if (cardNumber) {
                cardNumber.addEventListener('input', function() {
                    let value = this.value.replace(/\D/g, '');
                    let formatted = value.match(/.{1,4}/g);
                    this.value = formatted ? formatted.join(' ') : '';
                });
            }

            const cardExpiry = document.getElementById('card_expiry');
            if (cardExpiry) {
                cardExpiry.addEventListener('input', function() {
                    let value = this.value.replace(/\D/g, '');
                    if (value.length >= 2) {
                        this.value = value.substring(0, 2) + '/' + value.substring(2);
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
