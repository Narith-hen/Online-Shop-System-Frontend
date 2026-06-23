<!-- resources/views/admin/orders/index.blade.php -->

@extends('MainLayout')

@section('title', 'Orders')
@section('page_title', 'Orders')
@section('page_subtitle', 'Manage customer orders')

@section('content')

<div class="mb-6">
    <div class="flex flex-col md:flex-row gap-4">
        <input type="text" placeholder="Search by order ID or customer..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="card bg-white p-4">
        <p class="text-gray-600 text-sm">Total Orders</p>
        <p class="text-2xl font-bold text-gray-900">{{ $totalOrders ?? 0 }}</p>
    </div>
    <div class="card bg-white p-4">
        <p class="text-gray-600 text-sm">Pending</p>
        <p class="text-2xl font-bold text-yellow-600">{{ $pendingOrders ?? 0 }}</p>
    </div>
    <div class="card bg-white p-4">
        <p class="text-gray-600 text-sm">Completed</p>
        <p class="text-2xl font-bold text-green-600">{{ $completedOrders ?? 0 }}</p>
    </div>
    <div class="card bg-white p-4">
        <p class="text-gray-600 text-sm">Total Revenue</p>
        <p class="text-2xl font-bold text-blue-600">${{ number_format($totalRevenue ?? 0, 2) }}</p>
    </div>
</div>

<!-- Orders Table -->
<div class="card bg-white rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Order ID</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Customer</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Items</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Total</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Status</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Date</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders ?? [] as $order)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="py-3 px-4 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                        <td class="py-3 px-4">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $order->customer_name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $order->customer_email ?? 'N/A' }}</p>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">
                            {{ $order->items_count ?? 0 }} item(s)
                        </td>
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
                                    {{ ucfirst($order->status ?? 'Unknown') }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">{{ $order->created_at->format('M d, Y') ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.orders.show', $order->id ?? '#') }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.orders.edit', $order->id ?? '#') }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-6 px-4 text-center text-gray-500">
                            <i class="fas fa-shopping-cart text-3xl mb-2"></i>
                            <p class="text-sm mt-2">No orders yet</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(isset($orders) && method_exists($orders, 'links'))
        <div class="p-4 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
    @endif
</div>

@endsection