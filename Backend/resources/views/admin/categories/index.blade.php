@extends('MainLayout')

@section('title', 'Categories')
@section('page_title', 'Categories')
@section('page_subtitle', 'Manage your store categories')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">All Categories</h2>
        </div>
        <a href="{{ route('admin.categories.create') }}" 
           class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-medium transition">
            <i class="fas fa-plus"></i>
            Add New Category
        </a>
    </div>

    <!-- Main Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 w-16">ID</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Name</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Description</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Created At</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($categories as $index => $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700 font-medium">#{{ $index + 1 }}</td>
                    
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($category->image_url)
                                <img src="{{ $category->image_url }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-10 h-10 object-cover rounded">
                            @else
                                <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                            @endif
                            <span class="font-medium text-gray-800">{{ $category->name }}</span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-gray-600 text-sm">
                        {{ Str::limit($category->description ?? 'No description', 80) }}
                    </td>

                    <td class="px-6 py-4">
                        @if($category->is_active)
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Active</span>
                        @else
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Inactive</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $category->created_at->format('M d, Y') }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('admin.categories.edit', $category->id ?? '#') }}" 
                               class="text-blue-600 hover:text-blue-800 transition">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category->id ?? '#') }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this category?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <i class="fas fa-folder-open text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No categories yet</p>
                        <a href="{{ route('admin.categories.create') }}" 
                           class="text-blue-600 hover:underline mt-3 inline-block">
                            Create your first category
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
        <div class="flex justify-end mt-4">
            {{ $categories->links() }}
        </div>
    @endif

</div>
@endsection