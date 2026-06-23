<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">

    <title>@yield('title', 'Admin Dashboard') - Online Shop Admin</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
        }

        .sidebar {
            transition: transform 0.3s ease;
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .nav-link {
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: #1f2937;
            padding-left: 1.5rem;
        }

        .nav-link.active {
            background-color: #3b82f6;
            border-left: 4px solid #1e40af;
        }

        .dropdown-menu {
            display: none;
            transition: all 0.3s ease;
        }

        .dropdown-menu.show {
            display: block;
        }

        .card {
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100">
    <div class="flex h-screen bg-gray-100">

        <!-- Sidebar -->
        <aside
            class="w-64 bg-gray-900 text-gray-100 sidebar fixed md:relative h-full z-40 translate-x-full md:translate-x-0 transition-transform duration-300">
            <!-- Logo Section -->
            <div class="p-6 border-b border-gray-700">
                <div class="flex items-center gap-3">
                    <!-- Logo Image -->
                    <img src="{{ asset('images/logo.png') }}" alt="Online Shop Logo" class="w-10 h-10 object-contain rounded-3xl">
                    <div>
                        <h1 class="text-xl font-bold text-white">Online Shop</h1>
                        <p class="text-xs text-gray-400">Admin Panel</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="mt-8 px-4 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white @if (request()->routeIs('admin.dashboard')) active @endif">
                    <i class="fas fa-chart-line w-5"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Categories Section -->
                <div>
                    <button onclick="toggleDropdown('categories')"
                        class="w-full nav-link flex items-center justify-between gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white @if (request()->routeIs('admin.categories*')) active @endif">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-tag w-5"></i>
                            <span>Categories</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>
                    <div id="categories"
                        class="dropdown-menu @if (request()->routeIs('admin.categories*')) show @endif ml-4 mt-2 space-y-2">
                        <a href="{{ route('admin.categories.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded text-gray-400 hover:text-white hover:bg-gray-800 @if (request()->routeIs('admin.categories.index')) bg-gray-800 text-white @endif">
                            <i class="fas fa-list w-4"></i>
                            <span>All Categories</span>
                        </a>
                        <a href="{{ route('admin.categories.create') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded text-gray-400 hover:text-white hover:bg-gray-800 @if (request()->routeIs('admin.categories.create')) bg-gray-800 text-white @endif">
                            <i class="fas fa-plus w-4"></i>
                            <span>Add Category</span>
                        </a>
                    </div>
                </div>
                
                <!-- Products Section -->
                <div>
                    <button onclick="toggleDropdown('products')"
                        class="w-full nav-link flex items-center justify-between gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white @if (request()->routeIs('admin.products*')) active @endif">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-box w-5"></i>
                            <span>Products</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>
                    <div id="products"
                        class="dropdown-menu @if (request()->routeIs('admin.products*')) show @endif ml-4 mt-2 space-y-2">
                        <a href="{{ route('admin.products.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded text-gray-400 hover:text-white hover:bg-gray-800 @if (request()->routeIs('admin.products.index')) bg-gray-800 text-white @endif">
                            <i class="fas fa-list w-4"></i>
                            <span>All Products</span>
                        </a>
                        <a href="{{ route('admin.products.create') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded text-gray-400 hover:text-white hover:bg-gray-800 @if (request()->routeIs('admin.products.create')) bg-gray-800 text-white @endif">
                            <i class="fas fa-plus w-4"></i>
                            <span>Add Product</span>
                        </a>
                    </div>
                </div>


                <!-- Orders -->
                <a href="{{ route('admin.orders.index') }}"
                    class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white @if (request()->routeIs('admin.orders*')) active @endif">
                    <i class="fas fa-shopping-cart w-5"></i>
                    <span>Orders</span>
                </a>

                <!-- Divider -->
                <div class="border-t border-gray-700 my-4"></div>

                <!-- Settings -->
                <a href="{{ route('admin.settings') }}"
                    class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white @if (request()->routeIs('admin.settings')) active @endif">
                    <i class="fas fa-cog w-5"></i>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4 flex justify-between items-center">
                    <!-- Mobile Menu Toggle -->
                    <button onclick="toggleSidebar()" class="md:hidden text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <!-- Page Title -->
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">@yield('page_title', 'Dashboard')</h2>
                        <p class="text-sm text-gray-500 mt-1">@yield('page_subtitle', 'Welcome to your dashboard')</p>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center gap-4">
                        <!-- Notifications -->
                        <button class="relative text-gray-600 hover:text-gray-900 transition">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- User Dropdown -->
                        <div class="relative group">
                            <button class="flex items-center gap-2 text-gray-700 hover:text-gray-900">
                                <img src="https://via.placeholder.com/40x40?text=Admin" alt="User"
                                    class="w-10 h-10 rounded-full">
                                <span class="text-sm font-medium">{{ optional(Auth::user())->name ?? 'Admin' }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                    <i class="fas fa-user mr-2"></i> Profile
                                </a>
                                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i> Settings
                                </a>
                                <hr class="my-2">
                                <button onclick="logout()"
                                    class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 rounded-b-lg">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto">
                <div class="p-6">
                    <!-- Flash Messages -->
                    @if ($message = Session::get('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ $message }}
                        </div>
                    @endif

                    @if ($message = Session::get('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </div>
                    @endif

                    <!-- Page Content Section -->
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-4 px-6 text-center text-gray-600 text-sm">
        <p>&copy; 2024 Online Shop Admin Panel. All rights reserved.</p>
    </footer> --}}

    <!-- Scripts -->
    <script>
        // Toggle Sidebar for Mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full');
            }
        }

        // Toggle Dropdown Menus
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        }

        // Close sidebar when clicking nav link on mobile
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    const sidebar = document.querySelector('.sidebar');
                    if (sidebar) {
                        sidebar.classList.add('-translate-x-full');
                    }
                }
            });
        });

        // Logout Function
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '{{ route('admin.logout') }}';
            }
        }
    </script>

    @stack('scripts')
</body>

</html>
