<x-app-layout>
    <div x-data="{ showModal: false, deleteKey: null, showDeleteModal: false }" class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-8 flex items-center gap-3">
                <span>🛒</span> Carrito de Compras
            </h1>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-green-800 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span class="text-red-800 font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if(count($carrito) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Lista de Productos -->
                    <div class="lg:col-span-2 space-y-4">
                        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
                                <h2 class="font-bold text-gray-900 text-lg">Productos en tu carrito ({{ count($carrito) }})</h2>
                                
                                <!-- Boton Abrir Modal Vaciar -->
                                <button type="button" @click="showModal = true" class="text-sm text-red-600 hover:text-red-800 font-semibold transition flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Vaciar Carrito
                                </button>
                            </div>

                            <div class="divide-y divide-gray-100">
                                @foreach($carrito as $clave => $item)
                                    <div class="p-6 flex flex-col sm:flex-row items-center gap-4 sm:gap-6 hover:bg-gray-50/50 transition">
                                        <!-- Imagen -->
                                        <div class="w-24 h-24 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                            <img src="{{ asset($item['imagen']) }}" alt="{{ $item['nombre'] }}" class="w-full h-full object-cover">
                                        </div>

                                        <!-- Detalles del Producto -->
                                        <div class="flex-1 text-center sm:text-left">
                                            <a href="{{ route('productos.ver', $item['slug']) }}" class="font-bold text-gray-900 hover:text-purple-600 transition text-base">
                                                {{ $item['nombre'] }}
                                            </a>
                                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-1">
                                                @if(!empty($item['talla']))
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        Talla: {{ $item['talla'] }}
                                                    </span>
                                                @endif
                                                @if(!empty($item['color']))
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        Color: {{ $item['color'] }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-gray-500 text-sm mt-2">
                                                Precio unitario: <span class="font-semibold text-gray-700">₡{{ number_format($item['precio'], 0, ',', '.') }}</span>
                                            </p>
                                        </div>

                                        <!-- Controles de Cantidad y Subtotal -->
                                        <div class="flex flex-col sm:items-end items-center gap-3">
                                            <!-- Formulario de Cantidad -->
                                            <form action="{{ route('carrito.actualizar', $clave) }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                @method('PUT')
                                                <input type="number" name="cantidad" value="{{ $item['cantidad'] }}" min="1" max="99"
                                                    class="w-16 text-center border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm py-1">
                                                <button type="submit" title="Actualizar cantidad" class="p-1.5 bg-gray-100 hover:bg-purple-100 text-gray-600 hover:text-purple-700 rounded-lg transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                    </svg>
                                                </button>
                                            </form>

                                            <!-- Subtotal de item -->
                                            <div class="text-right">
                                                <span class="text-xs text-gray-400 uppercase tracking-wider block">Subtotal</span>
                                                <span class="text-lg font-bold text-purple-600">₡{{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                            </div>

                                            <!-- Boton Eliminar item -->
                                            <form action="{{ route('carrito.eliminar', $clave) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium underline transition">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Boton de Seguir Comprando -->
                        <div class="pt-2">
                            <a href="{{ route('productos.index') }}" class="inline-flex items-center text-purple-600 hover:text-purple-800 font-semibold transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Seguir Comprando
                            </a>
                        </div>
                    </div>

                    <!-- Resumen del Pedido -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden sticky top-8">
                            <div class="p-6 border-b border-gray-100 bg-white">
                                <h2 class="font-bold text-gray-900 text-lg">Resumen del Pedido</h2>
                            </div>
                            
                            <div class="p-6">
                                <!-- Barra Progreso Envio Gratis -->
                                <div class="mb-6 p-4 rounded-lg bg-purple-50 border border-purple-100">
                                    @if($subtotal > 50000)
                                        <div class="flex items-center text-green-700 font-medium text-sm">
                                            <span class="mr-2">🎉</span> ¡Felicidades! Tienes&nbsp;<strong>ENVÍO GRATIS</strong>.
                                        </div>
                                    @else
                                        @php
                                            $faltante = 50000 - $subtotal;
                                            $porcentaje = min(100, ($subtotal / 50000) * 100);
                                        @endphp
                                        <p class="text-xs text-purple-800 font-medium mb-2">
                                            Agrega <strong>₡{{ number_format($faltante, 0, ',', '.') }}</strong> más para obtener <strong>Envío Gratis</strong>.
                                        </p>
                                        <div class="w-full bg-purple-200 rounded-full h-2">
                                            <div class="bg-purple-600 h-2 rounded-full transition-all duration-300" style="width: {{ $porcentaje }}%"></div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Desglose de Costos -->
                                <div class="space-y-4 mb-6">
                                    <div class="flex justify-between text-gray-600 text-sm">
                                        <span>Subtotal</span>
                                        <span class="font-semibold text-gray-900">₡{{ number_format($subtotal, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-gray-600 text-sm">
                                        <span>IVA (13% Costa Rica)</span>
                                        <span class="font-semibold text-gray-900">₡{{ number_format($impuesto, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-gray-600 text-sm">
                                        <span>Envío</span>
                                        @if($envio == 0)
                                            <span class="font-bold text-green-600">GRATIS</span>
                                        @else
                                            <span class="font-semibold text-gray-900">₡{{ number_format($envio, 0, ',', '.') }}</span>
                                        @endif
                                    </div>

                                    <div class="pt-4 border-t border-gray-200 flex justify-between items-center">
                                        <span class="text-base font-bold text-gray-900">Total a pagar</span>
                                        <span class="text-2xl font-extrabold text-purple-600">₡{{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <!-- Boton de Checkout -->
                                <a href="{{ route('checkout.index') }}" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3.5 px-4 rounded-xl transition duration-200 text-center shadow-lg shadow-purple-200 block">
                                    Proceder al Pago →
                                </a>

                                <div class="mt-4 flex justify-center items-center gap-2 text-xs text-gray-400">
                                    <span>🔒 Compra 100% Segura y Protegida</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Estado Carrito Vacio -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center max-w-xl mx-auto my-8">
                    <div class="w-24 h-24 bg-purple-50 text-purple-500 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">
                        🛒
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Tu carrito está vacío</h2>
                    <p class="text-gray-500 mb-8">Parece que aún no has agregado productos a tu carrito. ¡Explora nuestro catálogo y descubre las últimas tendencias de moda!</p>
                    <a href="{{ route('productos.index') }}" class="inline-flex items-center justify-center bg-purple-600 hover:bg-purple-700 text-white font-bold py-3.5 px-8 rounded-xl shadow-md transition">
                        Ver Catálogo de Productos
                    </a>
                </div>
            @endif
        </div>

        <!-- MODAL PERSONALIZADO VACIAR CARRITO -->
        <div x-show="showModal" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-0"
             role="dialog" aria-modal="true">
            
            <!-- Backdrop Oscuro con Fondo Difuminado (Glassmorphism) -->
            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" 
                 @click="showModal = false"></div>

            <!-- Modal Content Card -->
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="relative bg-white rounded-3xl overflow-hidden shadow-2xl transform transition-all sm:max-w-lg sm:w-full border border-gray-100 p-6 sm:p-8 z-10">
                
                <div class="text-center sm:text-left">
                    <!-- Icono Animado de Alerta -->
                    <div class="mx-auto sm:mx-0 flex items-center justify-center h-16 w-16 rounded-2xl bg-purple-50 text-purple-600 mb-5">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        ¿Vaciar carrito de compras?
                    </h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">
                        Esta acción eliminará de forma permanente <strong class="font-bold text-gray-700">todos los productos</strong> que has agregado a tu carrito. No podrás deshacer este cambio.
                    </p>
                </div>

                <!-- Botones de Accion -->
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2">
                    <button type="button" 
                            @click="showModal = false" 
                            class="w-full sm:w-auto px-6 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 border border-gray-300 text-gray-900 font-bold focus:outline-none transition text-sm shadow-sm">
                        Cancelar
                    </button>
                    <form action="{{ route('carrito.vaciar') }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full sm:w-auto px-6 py-3 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold shadow-lg shadow-purple-200 focus:outline-none transition text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Sí, vaciar carrito
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
