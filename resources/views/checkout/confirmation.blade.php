<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Confirmacion de Compra
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-center mb-6">
                <div class="mb-4">
                    <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">¡Gracias por tu compra!</h1>
                <p class="text-gray-600 mb-4">Tu pedido ha sido procesado exitosamente.</p>
                <div class="inline-block bg-purple-100 text-purple-800 px-6 py-3 rounded-lg">
                    <span class="text-sm">Numero de Seguimiento</span>
                    <p class="text-2xl font-bold">{{ $pedido->numero_seguimiento }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalles del Pedido</h3>

                <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                    <div>
                        <span class="text-gray-500">Fecha:</span>
                        <span class="font-medium ml-2">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Estado:</span>
                        <span class="ml-2 inline-block px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            {{ $pedido->estado_label }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-500">Metodo de pago:</span>
                        <span class="font-medium ml-2">{{ $pedido->metodo_pago === 'paypal' ? 'PayPal' : 'Tarjeta (Simulado)' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Estado del pago:</span>
                        <span class="ml-2 inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $pedido->estado_pago === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $pedido->estado_pago_label }}
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <span class="text-gray-500 text-sm">Direccion de envio:</span>
                    <p class="text-sm font-medium">{{ $pedido->direccion_envio }}</p>
                </div>

                <hr class="my-4">

                <h4 class="font-semibold text-gray-800 mb-3">Productos</h4>
                <div class="space-y-3">
                    @foreach($pedido->items as $item)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset($item->producto->imagen ?? 'images/products/placeholder.jpg') }}" alt="{{ $item->producto->nombre }}" class="w-12 h-12 object-cover rounded">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $item->producto->nombre }}</p>
                                    <p class="text-sm text-gray-500">
                                        Cant: {{ $item->cantidad }}
                                        @if($item['talla']) | Talla: {{ $item['talla'] }} @endif
                                        @if($item['color']) | {{ $item['color'] }} @endif
                                    </p>
                                </div>
                            </div>
                            <span class="font-medium">₡{{ number_format($item->precio * $item->cantidad, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>

                <hr class="my-4">

                <div class="space-y-2 text-sm">
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
                        <span>Total Pagado</span>
                        <span class="text-purple-600">₡{{ number_format($pedido->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-center gap-4">
                <a href="{{ route('pedido.ver', $pedido) }}" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition font-semibold">
                    Ver Detalle del Pedido
                </a>
                <a href="{{ route('pedidos.index') }}" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Mis Pedidos
                </a>
                <a href="{{ route('inicio') }}" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Seguir Comprando
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
