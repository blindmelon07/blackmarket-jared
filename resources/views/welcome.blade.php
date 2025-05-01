<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Laravel Shop</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="text-gray-800 bg-gray-100 dark:bg-gray-900 dark:text-gray-100">

    {{-- Navbar --}}
    <header class="flex items-center justify-between p-6 bg-white shadow dark:bg-gray-800">
        <div class="text-2xl font-bold">
            <a href="/">MyShop</a>
        </div>
        <nav class="space-x-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="font-semibold text-indigo-600 dark:text-indigo-400">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:underline">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-gray-700 dark:text-gray-300 hover:underline">Register</a>
                @endif
            @endauth
        </nav>
    </header>

    {{-- Product Listings --}}
    <section class="px-6 py-12 mx-auto max-w-7xl">
        <h1 class="mb-8 text-3xl font-bold text-center">Featured Products</h1>

        @if ($products->count())
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($products as $product)
                <div class="transition bg-white rounded-lg shadow dark:bg-gray-800 hover:shadow-lg">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="object-cover w-full h-48 rounded-t-lg">
                    <div class="p-4">
                        <h3 class="mb-1 text-lg font-semibold">{{ $product->name }}</h3>
                        <p class="mb-2 text-sm text-gray-600 dark:text-gray-400">{{ $product->description }}</p>
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-indigo-600 dark:text-indigo-400">${{ $product->price }}</span>

                            @if ($product->user_id)
                                <a href="{{ route('contact.seller', ['sellerId' => $product->user_id]) }}"
                                   class="block px-4 py-1 text-sm text-center text-white bg-indigo-600 rounded hover:bg-indigo-700">
                                    Contact Seller
                                </a>
                            @else
                                <span class="block px-4 py-1 text-sm text-center text-white bg-gray-500 rounded">No Seller</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        @else
            <p class="text-center text-gray-500 dark:text-gray-400">No products available.</p>
        @endif
    </section>

    {{-- Footer --}}
    <footer class="py-6 text-sm text-center text-gray-600 bg-gray-100 dark:bg-gray-800 dark:text-gray-400">
        &copy; {{ date('Y') }} MyShop. All rights reserved.
    </footer>

</body>
</html>
