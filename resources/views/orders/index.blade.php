<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis Pedidos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($pedidos->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">No tienes pedidos aun</h3>
                    <p class="text-gray-500 mb-6">Explora nuestros productos y realiza tu primera compra.</p>
                    <a href="{{ route('productos.index') }}" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition font-semibold">
                        Ver Productos
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($pedidos as $pedido)
                        <a href="{{ route('pedido.ver', $pedido) }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="text-lg font-bold text-purple-600">{{ $pedido->numero_seguimiento }}</span>
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                            @if($pedido->estado === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($pedido->estado === 'processing') bg-blue-100 text-blue-800
                                            @elseif($pedido->estado === 'shipped') bg-indigo-100 text-indigo-800
                                            @elseif($pedido->estado === 'delivered') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif
                                        ">
                                            {{ $pedido->estado_label }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                                    <p class="text-sm text-gray-500 mt-1">{{ $pedido->items->count() }} producto(s) | {{ $pedido->metodo_pago === 'paypal' ? 'PayPal' : 'Tarjeta' }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xl font-bold text-gray-800">₡{{ number_format($pedido->total, 0, ',', '.') }}</span>
                                    <p class="text-sm text-gray-500">
                                        <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded-full {{ $pedido->estado_pago === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $pedido->estado_pago_label }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $pedidos->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
