<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-8">

                <!-- Sidebar Filtros -->
                <div class="w-full md:w-64 flex-shrink-0">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="font-bold text-lg mb-4">Filtros</h3>

                        <!-- Buscar -->
                        <form action="{{ route('products.search') }}" method="GET" class="mb-6">
                            <input type="text" name="q" placeholder="Buscar producto..."
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                value="{{ request('q') }}">
                            <button type="submit" class="mt-2 w-full bg-purple-600 text-white rounded-lg py-2 hover:bg-purple-700 transition">
                                Buscar
                            </button>
                        </form>

                        <!-- Categorias -->
                        <h4 class="font-semibold mb-2">Categorias</h4>
                        <div class="space-y-2 mb-6">
                            <a href="{{ route('products.index') }}" class="block text-sm {{ !request('categoria') ? 'text-purple-600 font-semibold' : 'text-gray-600 hover:text-purple-600' }}">
                                Todos
                            </a>
                            @foreach($categories as $category)
                                <a href="{{ route('products.index', ['categoria' => $category->id]) }}"
                                    class="block text-sm {{ request('categoria') == $category->id ? 'text-purple-600 font-semibold' : 'text-gray-600 hover:text-purple-600' }}">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>

                        <!-- Precio -->
                        <h4 class="font-semibold mb-2">Precio</h4>
                        <form action="{{ route('products.index') }}" method="GET" class="space-y-2">
                            <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                            <input type="number" name="precio_min" placeholder="Min"
                                class="w-full border-gray-300 rounded-lg shadow-sm text-sm"
                                value="{{ request('precio_min') }}">
                            <input type="number" name="precio_max" placeholder="Max"
                                class="w-full border-gray-300 rounded-lg shadow-sm text-sm"
                                value="{{ request('precio_max') }}">
                            <button type="submit" class="w-full bg-gray-600 text-white rounded-lg py-2 text-sm hover:bg-gray-700 transition">
                                Filtrar Precio
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Lista de Productos -->
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">Catalogo de Productos</h1>

                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($products as $product)
                                <a href="{{ route('products.show', $product->slug) }}" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                                    <div class="h-48 bg-gray-200">
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                    </div>
                                    <div class="p-4">
                                        <span class="text-xs text-purple-600 font-semibold">{{ $product->category->name }}</span>
                                        <h3 class="font-semibold text-gray-800 mt-1">{{ $product->name }}</h3>
                                        <p class="text-gray-500 text-sm mt-1 line-clamp-2">{{ $product->description }}</p>
                                        <div class="mt-3 flex justify-between items-center">
                                            <p class="text-lg font-bold text-purple-600">₡{{ number_format($product->price, 0, ',', '.') }}</p>
                                            @if($product->stock > 0)
                                                <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded">En stock</span>
                                            @else
                                                <span class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded">Agotado</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-lg shadow-md p-8 text-center">
                            <p class="text-gray-500 text-lg">No se encontraron productos.</p>
                            <a href="{{ route('products.index') }}" class="mt-4 inline-block text-purple-600 hover:text-purple-800 font-semibold">
                                Ver todos los productos
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
