<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mis Pedidos
            </h2>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('reportes.cliente') }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Historial Cliente (PDF)
                </a>
                <form action="{{ route('reportes.ventas') }}" method="GET" target="_blank" class="flex items-center gap-2">
                    <select name="mes" class="text-xs rounded-lg border-gray-300 py-1.5 px-2">
                        <option value="1" {{ date('n') == 1 ? 'selected' : '' }}>Enero</option>
                        <option value="2" {{ date('n') == 2 ? 'selected' : '' }}>Febrero</option>
                        <option value="3" {{ date('n') == 3 ? 'selected' : '' }}>Marzo</option>
                        <option value="4" {{ date('n') == 4 ? 'selected' : '' }}>Abril</option>
                        <option value="5" {{ date('n') == 5 ? 'selected' : '' }}>Mayo</option>
                        <option value="6" {{ date('n') == 6 ? 'selected' : '' }}>Junio</option>
                        <option value="7" {{ date('n') == 7 ? 'selected' : '' }}>Julio</option>
                        <option value="8" {{ date('n') == 8 ? 'selected' : '' }}>Agosto</option>
                        <option value="9" {{ date('n') == 9 ? 'selected' : '' }}>Septiembre</option>
                        <option value="10" {{ date('n') == 10 ? 'selected' : '' }}>Octubre</option>
                        <option value="11" {{ date('n') == 11 ? 'selected' : '' }}>Noviembre</option>
                        <option value="12" {{ date('n') == 12 ? 'selected' : '' }}>Diciembre</option>
                    </select>
                    <select name="anio" class="text-xs rounded-lg border-gray-300 py-1.5 px-2">
                        <option value="2026" selected>2026</option>
                        <option value="2025">2025</option>
                    </select>
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Reporte Ventas Mes (PDF)
                    </button>
                </form>
            </div>
        </div>
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
