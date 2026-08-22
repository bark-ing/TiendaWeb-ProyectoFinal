<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pedido {{ $pedido->numero_seguimiento }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <span class="text-sm text-gray-500">Numero de Seguimiento</span>
                        <p class="text-2xl font-bold text-purple-600">{{ $pedido->numero_seguimiento }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                            @if($pedido->estado === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($pedido->estado === 'processing') bg-blue-100 text-blue-800
                            @elseif($pedido->estado === 'shipped') bg-indigo-100 text-indigo-800
                            @elseif($pedido->estado === 'delivered') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif
                        ">
                            {{ $pedido->estado_label }}
                        </span>
                        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full {{ $pedido->estado_pago === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $pedido->estado_pago_label }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm mb-6">
                    <div>
                        <span class="text-gray-500">Fecha del pedido:</span>
                        <p class="font-medium">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Metodo de pago:</span>
                        <p class="font-medium">{{ $pedido->metodo_pago === 'paypal' ? 'PayPal' : 'Tarjeta (Simulado)' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Direccion de envio:</span>
                        <p class="font-medium">{{ $pedido->direccion_envio }}</p>
                    </div>
                </div>

                <hr class="my-4">

                <h4 class="font-semibold text-gray-800 mb-3">Productos</h4>
                <div class="space-y-3 mb-6">
                    @foreach($pedido->items as $item)
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-4">
                                <img src="{{ asset($item->producto->imagen ?? 'images/products/placeholder.jpg') }}" alt="{{ $item->producto->nombre }}" class="w-16 h-16 object-cover rounded">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $item->producto->nombre }}</p>
                                    <p class="text-sm text-gray-500">
                                        Precio unitario: ₡{{ number_format($item->precio, 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Cant: {{ $item->cantidad }}
                                        @if($item['talla']) | Talla: {{ $item['talla'] }} @endif
                                        @if($item['color']) | {{ $item['color'] }} @endif
                                    </p>
                                </div>
                            </div>
                            <span class="font-bold text-gray-800">₡{{ number_format($item->precio * $item->cantidad, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>

                <hr class="my-4">

                <div class="space-y-2 text-sm max-w-xs ml-auto">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span>₡{{ number_format($pedido->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">IVA (13%)</span>
                        <span>₡{{ number_format($pedido->impuesto, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Envio</span>
                        <span>{{ $pedido->costo_envio > 0 ? '₡' . number_format($pedido->costo_envio, 0, ',', '.') : 'Gratis' }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span class="text-purple-600">₡{{ number_format($pedido->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('pedido.factura', $pedido) }}" target="_blank" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Descargar Factura (PDF)
                </a>
                <a href="{{ route('pedidos.index') }}" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Volver a Mis Pedidos
                </a>
                <a href="{{ route('inicio') }}" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition font-semibold">
                    Seguir Comprando
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
