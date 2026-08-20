<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Hero Banner -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg shadow-lg mb-8 p-8 text-center">
                <h1 class="text-4xl font-bold text-white mb-4">Vexians Boutique</h1>
                <p class="text-xl text-purple-100">Descubre la moda que define tu estilo</p>
                <a href="{{ route('products.index') }}" class="mt-4 inline-block bg-white text-purple-600 font-semibold px-6 py-3 rounded-lg hover:bg-purple-50 transition">
                    Ver Catalogo
                </a>
            </div>

            <!-- Categorias -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Categorias</h2>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    @foreach($categories as $category)
                        <a href="{{ route('products.category', $category->slug) }}" class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition">
                            <div class="text-3xl mb-2">
                                @if($category->name == 'Mujer') 👗
                                @elseif($category->name == 'Hombre') 👔
                                @elseif($category->name == 'Accesorios') 👜
                                @elseif($category->name == 'Calzado') 👟
                                @elseif($category->name == 'Deportiva') ⚽
                                @endif
                            </div>
                            <h3 class="font-semibold text-gray-800">{{ $category->name }}</h3>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Ultimos Productos -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Ultimos Productos</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <a href="{{ route('products.show', $product->slug) }}" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                            <div class="h-48 bg-gray-200 flex items-center justify-center">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-1">{{ $product->name }}</h3>
                                <p class="text-gray-500 text-sm mb-2">{{ $product->category->name }}</p>
                                <p class="text-lg font-bold text-purple-600">₡{{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('products.index') }}" class="text-purple-600 hover:text-purple-800 font-semibold">
                        Ver todos los productos →
                    </a>
                </div>
            </div>

            <!-- Vistos Recientemente -->
            @if($recentProducts->count() > 0)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Vistos Recientemente</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($recentProducts as $recent)
                            <a href="{{ route('products.show', $recent->slug) }}" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                                <div class="h-32 bg-gray-200">
                                    <img src="{{ asset($recent->image) }}" alt="{{ $recent->name }}" class="h-full w-full object-cover">
                                </div>
                                <div class="p-3">
                                    <h4 class="font-semibold text-gray-800 text-sm">{{ $recent->name }}</h4>
                                    <p class="text-purple-600 font-bold">₡{{ number_format($recent->price, 0, ',', '.') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
