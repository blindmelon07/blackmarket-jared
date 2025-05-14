<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Black Market - Second Hand Marketplace</title>
    @php
        use Illuminate\Support\Facades\Storage;
    @endphp
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .search-transition { transition: all 0.3s ease-in-out; }
        .modal-transition { transition: all 0.3s ease-out; }
        /* Responsive text sizes */
        @media (max-width: 640px) {
            .text-responsive {
                font-size: 0.875rem;
            }
        }
        /* Custom scrollbar for mobile devices */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50" 
      x-data="{
          searchOpen: false,
          mobileMenu: false,
          selectedProduct: null,
          showProductModal: false,
          activeCategory: null,
          searchQuery: '',
          showDashboard: false,
          currentView: 'home',
          init() {
              this.$watch('selectedProduct', (value) => {
                  if (value) {
                      this.showProductModal = true;
                  }
              });
          }
      }">
    {{-- Top Bar --}}
    <div class="bg-indigo-600">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-10 text-white text-xs sm:text-sm">
                <p class="hidden sm:block">Free shipping for orders over ₱50</p>
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <a href="#" class="hover:text-indigo-200">Track Order</a>
                    <a href="#" class="hover:text-indigo-200">Help Center</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Navigation --}}
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <div class="flex items-center">
                    <a href="/" class="text-xl sm:text-2xl font-bold text-indigo-600 flex items-center space-x-2">
                        <i class="fas fa-store"></i>
                        <span>Black Market</span>
                    </a>
                </div>

                {{-- Search Bar --}}
                <div class="hidden md:flex flex-1 max-w-2xl mx-4 lg:mx-8">
                    <div class="relative w-full">
                        <input type="text" 
                               placeholder="Search for products..." 
                               class="w-full pl-10 pr-4 py-2 text-sm rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                </div>

                {{-- Desktop Navigation --}}
                <nav class="hidden md:flex items-center space-x-4 lg:space-x-6">
                    @auth
                        <a href="{{ route('filament.admin.pages.dashboard') }}" 
                           class="flex items-center text-sm text-gray-700 hover:text-indigo-600">
                            <i class="fas fa-user-circle mr-2"></i>
                            <span class="hidden lg:inline">Dashboard</span>
                        </a>
                        <form method="POST" action="{{ route('filament.admin.auth.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center text-sm text-gray-700 hover:text-indigo-600">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                <span class="hidden lg:inline">Logout</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('filament.admin.auth.login') }}" 
                           class="flex items-center text-sm text-gray-700 hover:text-indigo-600">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            <span class="hidden lg:inline">Login</span>
                        </a>
                        @if (Route::has('filament.admin.auth.register'))
                            <a href="{{ route('filament.admin.auth.register') }}" 
                               class="flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                                <i class="fas fa-user-plus mr-2"></i>
                                <span class="hidden sm:inline">Register</span>
                            </a>
                        @endif
                    @endauth
                </nav>

                {{-- Mobile Menu Button --}}
                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-bars text-gray-600"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenu" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden bg-white border-t">
            <div class="container mx-auto px-4 py-4 space-y-4">
                <div class="relative">
                    <input type="text" 
                           placeholder="Search for products..." 
                           class="w-full pl-10 pr-4 py-2 text-sm rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                @auth
                    <a href="{{ route('filament.admin.pages.dashboard') }}" 
                       class="block py-2 text-sm text-gray-700 hover:text-indigo-600">
                        <i class="fas fa-user-circle mr-2"></i>
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('filament.admin.auth.logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 text-sm text-gray-700 hover:text-indigo-600">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('filament.admin.auth.login') }}" 
                       class="block py-2 text-sm text-gray-700 hover:text-indigo-600">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login
                    </a>
                    @if (Route::has('filament.admin.auth.register'))
                        <a href="{{ route('filament.admin.auth.register') }}" 
                           class="block py-2 text-sm text-gray-700 hover:text-indigo-600">
                            <i class="fas fa-user-plus mr-2"></i>
                            Register
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </header>

    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-r from-indigo-600 to-indigo-800 py-12 sm:py-16 md:py-20">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4 sm:mb-6">
                    Find Amazing Deals on Second-Hand Items
                </h1>
                <p class="text-lg sm:text-xl text-indigo-100 mb-6 sm:mb-8">
                    Buy and sell pre-loved items in a safe and secure marketplace
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4">
                    <a href="#" class="px-6 sm:px-8 py-3 bg-white text-indigo-600 rounded-lg font-medium hover:bg-gray-100 transition-colors text-sm sm:text-base">
                        Start Shopping
                    </a>
                    <a href="#" class="px-6 sm:px-8 py-3 bg-indigo-700 text-white rounded-lg font-medium hover:bg-indigo-800 transition-colors text-sm sm:text-base">
                        Sell Your Items
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Categories Section --}}
    <section class="py-8 sm:py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-6 sm:mb-8">Shop by Category</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 sm:gap-6">
                @foreach($categories as $category)
                    <a href="#" 
                       @click.prevent="activeCategory = {{ $category->id }}; currentView = 'products'"
                       class="group"
                       :class="{ 'ring-2 ring-indigo-600': activeCategory === {{ $category->id }} }">
                        <div class="aspect-square rounded-lg bg-gray-100 flex items-center justify-center group-hover:bg-indigo-50 transition-colors">
                            <i class="fas {{ $category->icon ?? 'fa-tag' }} text-2xl sm:text-4xl text-indigo-600"></i>
                        </div>
                        <p class="mt-2 text-center text-sm sm:text-base text-gray-600 group-hover:text-indigo-600">{{ $category->name }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Products Section --}}
    <section class="py-8 sm:py-12 bg-gray-50" x-show="currentView === 'products' || currentView === 'home'">
        <div class="container mx-auto px-4">
          <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-2 sm:space-y-0">
    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">
        <span x-show="currentView === 'home'">Featured Products</span>
        <span x-show="currentView === 'products'">Products</span>
    </h2>
    
    <span x-show="activeCategory" class="text-sm text-gray-500">
        in <span class="font-medium" x-text="categories.find(c => c.id === activeCategory)?.name"></span>
    </span>

    <template x-if="selectedProduct">
        <div class="text-sm text-gray-600 flex items-center space-x-2">
            <span>Seller:</span>
            <span class="font-medium text-gray-800" x-text="selectedProduct.user?.name || 'Unknown'"></span>
            <span class="text-yellow-500">
                <i class="fas fa-star"></i>
                <span x-text="selectedProduct.user?.trust_score ?? 0"></span>/5
            </span>
        </div>
    </template>
</div>
                <button x-show="currentView === 'products'" 
                        @click="currentView = 'home'; activeCategory = null" 
                        class="text-indigo-600 hover:text-indigo-800">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span class="text-sm sm:text-base">Back to Home</span>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 md:gap-8">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden group hover:shadow-md transition-shadow product-card cursor-pointer"
                         @click="selectedProduct = {
                            id: {{ $product->id }},
                            name: '{{ addslashes($product->name) }}',
                            description: '{{ addslashes($product->description) }}',
                            price: {{ $product->price }},
                            original_price: {{ $product->original_price ?? 'null' }},
                            image: '{{ $product->image }}',
                            is_negotiable: {{ $product->is_negotiable ? 'true' : 'false' }},
                            user_id: {{ $product->user_id ?? 'null' }},
                            category_id: {{ $product->category_id }}
                         }; showProductModal = true"
                         data-category="{{ $product->category_id }}"
                         x-show="currentView === 'home' || (currentView === 'products' && (!activeCategory || activeCategory === {{ $product->category_id }}))"
                    >
                        <div class="relative">
                            <img src="{{ $product->image ? Storage::url($product->image) : asset('images/placeholder.jpg') }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-40 sm:h-48 object-cover group-hover:scale-105 transition-transform duration-300 product-image">
                            @if($product->is_negotiable)
                                <div class="absolute top-2 right-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-comments-dollar mr-1"></i>
                                        Negotiable
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="p-3 sm:p-4">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-1 product-name">{{ $product->name }}</h3>
                            <p class="text-xs sm:text-sm text-gray-500 mb-3 line-clamp-2 product-description">{{ $product->description }}</p>
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-base sm:text-lg font-bold text-indigo-600 product-price">₱{{ $product->price }}</span>
                                    @if($product->original_price)
                                        <span class="text-xs sm:text-sm text-gray-500 line-through">₱{{ $product->original_price }}</span>
                                    @endif
                                </div>
                                @if($product->user_id)
                                    <button @click.stop="window.location.href = '/contact/{{ $product->user_id }}'"
                                            class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100">
                                        <i class="fas fa-comments mr-1 sm:mr-2"></i>
                                        Contact
                                    </button>
                                @else
                                    <span class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-medium text-gray-500 bg-gray-100 rounded-lg">
                                        <i class="fas fa-store-slash mr-1 sm:mr-2"></i>
                                        No Seller
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-8 sm:py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8">
                <div class="text-center">
                    <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-shield-alt text-xl sm:text-2xl text-indigo-600"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Secure Trading</h3>
                    <p class="text-sm text-gray-500">Safe and secure marketplace with buyer protection</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-comments text-xl sm:text-2xl text-indigo-600"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Direct Communication</h3>
                    <p class="text-sm text-gray-500">Chat directly with sellers for the best deals</p>
                </div>
                <div class="text-center sm:col-span-2 md:col-span-1">
                    <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-hand-holding-usd text-xl sm:text-2xl text-indigo-600"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Great Value</h3>
                    <p class="text-sm text-gray-500">Find amazing deals on pre-loved items</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-300">
        <div class="container mx-auto px-4 py-8 sm:py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-white mb-4">About Us</h3>
                    <p class="text-sm text-gray-400">Black Market is your trusted marketplace for buying and selling second-hand items.</p>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-white mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white">Home</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Shop</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Categories</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Sell Items</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-white mb-4">Customer Service</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Shipping Info</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Returns</a></li>
                    </ul>
                </div>
                <div class="sm:col-span-2 lg:col-span-1">
                    <h3 class="text-base sm:text-lg font-semibold text-white mb-4">Newsletter</h3>
                    <p class="text-sm text-gray-400 mb-4">Subscribe to get updates on new products and special offers.</p>
                    <form class="flex">
                        <input type="email" 
                               placeholder="Enter your email" 
                               class="flex-1 px-3 py-2 text-sm rounded-l-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-r-lg hover:bg-indigo-700">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col sm:flex-row justify-between items-center">
                <p class="text-sm text-gray-400">&copy; {{ date('Y') }} Black Market. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 sm:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white text-sm">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm">
                        <i class="fab fa-pinterest"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Product Detail Modal --}}
    <div x-show="showProductModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="modal-transition"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="modal-transition"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg md:max-w-xl lg:max-w-2xl w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg sm:text-xl font-bold text-gray-900" x-text="selectedProduct?.name"></h3>
                                <button @click="showProductModal = false" class="text-gray-400 hover:text-gray-500">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                <div class="relative">
                                    <img :src="selectedProduct?.image ? '{{ Storage::url('') }}' + selectedProduct.image : '{{ asset('images/placeholder.jpg') }}'" 
                                         :alt="selectedProduct?.name"
                                         class="w-full h-48 sm:h-64 object-cover rounded-lg">
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-4" x-text="selectedProduct?.description"></p>
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <span class="text-xl sm:text-2xl font-bold text-indigo-600" x-text="'₱' + selectedProduct?.price"></span>
                                            <span class="text-sm text-gray-500 line-through ml-2" x-text="selectedProduct?.original_price ? '₱' + selectedProduct?.original_price : ''"></span>
                                        </div>
                                        <span x-show="selectedProduct?.is_negotiable" 
                                              class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-comments-dollar mr-1"></i>
                                            Negotiable
                                        </span>
                                    </div>
                                    <div class="space-y-3">
                                        <button class="w-full px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">
                                            <i class="fas fa-comments mr-2"></i>
                                            Contact Seller
                                        </button>
                                        <button class="w-full px-4 py-2 border border-indigo-600 text-sm text-indigo-600 rounded-lg hover:bg-indigo-50">
                                            <i class="fas fa-heart mr-2"></i>
                                            Save Item
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- User Dashboard Section --}}
    <div x-show="currentView === 'dashboard'" 
         x-cloak
         class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">My Dashboard</h2>
                <button @click="currentView = 'home'" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">My Listings</h3>
                    <div class="space-y-4">
                        <!-- Sample listing -->
                        <div class="flex items-center space-x-4">
                            <img src="https://via.placeholder.com/50" alt="Product" class="w-12 h-12 rounded-lg object-cover">
                            <div>
                                <p class="font-medium text-gray-900">Product Name</p>
                                <p class="text-sm text-gray-500">₱99.99</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Saved Items</h3>
                    <div class="space-y-4">
                        <!-- Sample saved item -->
                        <div class="flex items-center space-x-4">
                            <img src="https://via.placeholder.com/50" alt="Product" class="w-12 h-12 rounded-lg object-cover">
                            <div>
                                <p class="font-medium text-gray-900">Saved Product</p>
                                <p class="text-sm text-gray-500">₱149.99</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Messages</h3>
                    <div class="space-y-4">
                        <!-- Sample message -->
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-user text-indigo-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">John Doe</p>
                                <p class="text-sm text-gray-500">Interested in your item...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // Add click handlers to product cards
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', function() {
                const productId = this.dataset.productId;
                // Fetch product details and show modal
                // This would be replaced with actual API call
                const product = {
                    id: productId,
                    name: this.querySelector('.product-name').textContent,
                    description: this.querySelector('.product-description').textContent,
                    price: this.querySelector('.product-price').textContent.replace('₱', ''),
                    image: this.querySelector('.product-image').src,
                    is_negotiable: this.dataset.negotiable === 'true'
                };
                this.__x.$data.selectedProduct = product;
                this.__x.$data.showProductModal = true;
            });
        });
    </script>
</body>
</html>
