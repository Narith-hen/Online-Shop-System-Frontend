<!-- resources/views/admin/dashboard.blade.php -->

@extends('MainLayout')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Welcome back! Here\'s what\'s happening in your store')

@section('content')

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Products -->
    <div class="card bg-white p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Products</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalProducts ?? 0 }}</p>
                <p class="text-xs text-green-600 mt-2">
                    <i class="fas fa-arrow-up"></i> +2 this month
                </p>
            </div>
            <div class="bg-blue-100 p-4 rounded-lg">
                <i class="fas fa-box text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>

    <!-- Total Categories -->
    <div class="card bg-white p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Categories</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalCategories ?? 0 }}</p>
                <p class="text-xs text-green-600 mt-2">
                    <i class="fas fa-arrow-up"></i> +1 this month
                </p>
            </div>
            <div class="bg-purple-100 p-4 rounded-lg">
                <i class="fas fa-tag text-2xl text-purple-600"></i>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="card bg-white p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Orders</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalOrders ?? 0 }}</p>
                <p class="text-xs text-green-600 mt-2">
                    <i class="fas fa-arrow-up"></i> +12 this month
                </p>
            </div>
            <div class="bg-green-100 p-4 rounded-lg">
                <i class="fas fa-shopping-cart text-2xl text-green-600"></i>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="card bg-white p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($totalRevenue ?? 0, 2) }}</p>
                <p class="text-xs text-green-600 mt-2">
                    <i class="fas fa-arrow-up"></i> +15% this month
                </p>
            </div>
            <div class="bg-yellow-100 p-4 rounded-lg">
                <i class="fas fa-dollar-sign text-2xl text-yellow-600"></i>
            </div>
        </div>
    </div>

</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Sales Chart -->
    <div class="lg:col-span-2 card bg-white p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Sales Overview</h3>
            <select class="text-sm border border-gray-300 rounded px-3 py-1">
                <option>This Month</option>
                <option>Last Month</option>
                <option>Last 3 Months</option>
            </select>
        </div>
        <div class="h-64 bg-gradient-to-br from-blue-50 to-blue-100 rounded flex items-center justify-center">
            <div class="text-center text-gray-500">
                <i class="fas fa-chart-line text-4xl mb-2"></i>
                <p class="text-sm">Chart visualization would go here</p>
                <p class="text-xs text-gray-400 mt-1">Integration with Chart.js recommended</p>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="card bg-white p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Top Products</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between pb-3 border-b">
                <div>
                    <p class="text-sm font-medium text-gray-900">Wireless Headphones</p>
                    <p class="text-xs text-gray-500">245 sales</p>
                </div>
                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">+15%</span>
            </div>
            <div class="flex items-center justify-between pb-3 border-b">
                <div>
                    <p class="text-sm font-medium text-gray-900">USB-C Cable</p>
                    <p class="text-xs text-gray-500">189 sales</p>
                </div>
                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">+8%</span>
            </div>
            <div class="flex items-center justify-between pb-3 border-b">
                <div>
                    <p class="text-sm font-medium text-gray-900">Phone Stand</p>
                    <p class="text-xs text-gray-500">156 sales</p>
                </div>
                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">-3%</span>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-900">Screen Protector</p>
                    <p class="text-xs text-gray-500">132 sales</p>
                </div>
                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">+5%</span>
            </div>
        </div>
    </div>

</div>

<!-- Recent Orders -->
<div class="card bg-white p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900">Recent Orders</h3>
        <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
            View All <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Order ID</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Customer</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Amount</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Status</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Date</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders ?? [] as $order)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                        <td class="py-3 px-4 text-sm text-gray-600">{{ $order->customer_name ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-sm font-semibold text-gray-900">${{ number_format($order->total ?? 0, 2) }}</td>
                        <td class="py-3 px-4 text-sm">
                            @if(($order->status ?? '') === 'completed')
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                                    Completed
                                </span>
                            @elseif(($order->status ?? '') === 'pending')
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-medium">
                                    Pending
                                </span>
                            @else
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-medium">
                                    {{ $order->status ?? 'Unknown' }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">{{ $order->created_at->format('M d, Y') ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-sm">
                            <a href="{{ route('admin.orders.show', $order->id ?? '#') }}" 
                               class="text-blue-600 hover:text-blue-900 font-medium">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-6 px-4 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p class="text-sm mt-2">No orders yet</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // You can add Chart.js or any other scripts here
    console.log('Dashboard loaded');
</script>
@endpush