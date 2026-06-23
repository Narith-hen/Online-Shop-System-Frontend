<!-- resources/views/admin/products/index.blade.php -->

@extends('MainLayout')

@section('title', 'Products')
@section('page_title', 'Products')
@section('page_subtitle', 'Manage your store products')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
        <i class="fas fa-plus"></i>
        Add New Product
    </a>
</div>

<div class="card bg-white rounded-lg overflow-hidden">
    <div class="p-6">
        <!-- Search and Filter -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <input type="text" placeholder="Search products..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Categories</option>
                <option value="electronics">Electronics</option>
                <option value="accessories">Accessories</option>
            </select>
        </div>

        <!-- Products Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Product Name</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Category</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Price</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Stock</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Status</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products ?? [] as $product)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->image_url ?? 'https://via.placeholder.com/40x40?text=Product' }}" alt="{{ $product->name }}" class="w-10 h-10 rounded object-cover">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500">ID: #{{ $product->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $product->category->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-900">${{ number_format($product->price ?? 0, 2) }}</td>
                            <td class="py-3 px-4 text-sm">
                                @if(($product->stock ?? 0) > 0)
                                    <span class="text-green-600">{{ $product->stock }} in stock</span>
                                @else
                                    <span class="text-red-600">Out of stock</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-sm">
                                @if($product->is_active)
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">Active</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-medium">Inactive</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.products.edit', $product->id ?? '#') }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id ?? '#') }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 px-4 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p class="text-sm mt-2">No products found</p>
                                <a href="{{ route('admin.products.create') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium mt-2 inline-block">
                                    Create your first product
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection