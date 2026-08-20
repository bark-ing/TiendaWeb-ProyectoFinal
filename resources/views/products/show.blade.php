<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="md:flex">
                    <!-- Imagen -->
                    <div class="md:w-1/2">
                        <div class="h-96 md:h-full bg-gray-200">
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                        </div>
                    </div>

                    <!-- Detalles -->
                    <div class="md:w-1/2 p-8">
                        <span class="text-sm text-purple-600 font-semibold">{{ $product->category->name }}</span>
                        <h1 class="text-3xl font-bold text-gray-800 mt-2">{{ $product->name }}</h1>

                        <div class="mt-4">
                            @if($product->stock > 0)
                                <span class="text-sm text-green-600 bg-green-100 px-3 py-1 rounded-full">En stock ({{ $product->stock }} disponibles)</span>
                            @else
                                <span class="text-sm text-red-600 bg-red-100 px-3 py-1 rounded-full">Agotado</span>
                            @endif
                        </div>

                        <p class="text-3xl font-bold text-purple-600 mt-4">₡{{ number_format($product->price, 0, ',', '.') }}</p>

                        <div class="mt-6">
                            <h3 class="font-semibold text-gray-800 mb-2">Descripcion</h3>
                            <p class="text-gray-600">{{ $product->description }}</p>
                        </div>

                        @if($product->stock > 0)
                            <form action="#" method="POST" class="mt-6">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <!-- Talla -->
                                @if($product->sizes)
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Talla</label>
                                        <select name="size" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                            @foreach($product->sizes as $size)
                                                <option value="{{ $size }}">{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <!-- Color -->
                                @if($product->colors)
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                                        <select name="color" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                            @foreach($product->colors as $color)
                                                <option value="{{ $color }}">{{ $color }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <!-- Cantidad -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Cantidad</label>
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                </div>

                                <button type="submit" class="w-full bg-purple-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-purple-700 transition">
                                    Agregar al Carrito
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('products.index') }}" class="mt-4 inline-block text-purple-600 hover:text-purple-800">
                            ← Volver al catalogo
                        </a>
                    </div>
                </div>
            </div>

            <!-- Vistos Recientemente -->
            @php
                $idsCrudos = json_decode(request()->cookie('recently_viewed', '[]'), true);
            @endphp
            @if(!empty($idsCrudos) && count($idsCrudos) > 1)
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Vistos Recientemente</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($idsCrudos as $id)
                            @php
                                $recentProduct = \App\Models\Product::find($id);
                            @endphp
                            @if($recentProduct && $recentProduct->id != $product->id)
                                <a href="{{ route('products.show', $recentProduct->slug) }}" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                                    <div class="h-32 bg-gray-200">
                                        <img src="{{ asset($recentProduct->image) }}" alt="{{ $recentProduct->name }}" class="h-full w-full object-cover">
                                    </div>
                                    <div class="p-3">
                                        <h4 class="font-semibold text-gray-800 text-sm">{{ $recentProduct->name }}</h4>
                                        <p class="text-purple-600 font-bold">₡{{ number_format($recentProduct->price, 0, ',', '.') }}</p>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
