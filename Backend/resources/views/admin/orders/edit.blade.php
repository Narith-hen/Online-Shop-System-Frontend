@extends('MainLayout')

@section('title', 'Edit Order #' . $order->id)
@section('page_title', 'Edit Order #' . $order->id)
@section('page_subtitle', 'Update order status')

@section('content')

<div class="space-y-6">

    <!-- Back Link -->
    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
        <i class="fas fa-arrow-left mr-1"></i> Back to Order
    </a>

    <div class="bg-white rounded-lg shadow p-6 max-w-3xl">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Update Order Status</h3>

        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="p-4 bg-red-100 text-red-700 border border-red-300 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Order Info -->
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500 text-sm">Order ID</span>
                        <span class="font-semibold text-gray-900">#{{ $order->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 text-sm">Customer</span>
                        <span class="font-semibold text-gray-900">{{ $order->customer_name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 text-sm">Total</span>
                        <span class="font-bold text-blue-600">${{ number_format($order->total, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 text-sm">Placed on</span>
                        <span class="font-semibold text-gray-900">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <!-- Status Select -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="pending" @selected(old('status', $order->status) === 'pending')>Pending</option>
                        <option value="processing" @selected(old('status', $order->status) === 'processing')>Processing</option>
                        <option value="shipped" @selected(old('status', $order->status) === 'shipped')>Shipped</option>
                        <option value="completed" @selected(old('status', $order->status) === 'completed')>Completed</option>
                        <option value="cancelled" @selected(old('status', $order->status) === 'cancelled')>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg">
                    <i class="fas fa-save mr-1"></i> Update Status
                </button>
                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
