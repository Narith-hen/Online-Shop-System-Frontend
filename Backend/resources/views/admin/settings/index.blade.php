@extends('MainLayout')

@section('title', 'Settings')
@section('page_title', 'Settings')
@section('page_subtitle', 'Manage your store configuration')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="flex space-x-8">
            <button onclick="switchTab(0)" 
                    class="tab-button active pb-4 text-sm font-medium border-b-2 border-blue-600 text-blue-600">
                General
            </button>
            <button onclick="switchTab(1)" 
                    class="tab-button pb-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                Contact Info
            </button>
            <button onclick="switchTab(2)" 
                    class="tab-button pb-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                Payment
            </button>
            <button onclick="switchTab(3)" 
                    class="tab-button pb-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                Shipping
            </button>
            <button onclick="switchTab(4)" 
                    class="tab-button pb-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                SEO
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <div id="tab-content">
        <!-- General Settings -->
        <div class="tab-panel">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">Store Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Store Name</label>
                            <input type="text" value="Online Shop" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                            <input type="text" value="Best products at best prices" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                            <div class="flex items-center gap-4">
                                <img src="https://via.placeholder.com/120x40?text=Logo" class="h-10">
                                <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm">Upload New</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">Appearance</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Primary Color</label>
                            <input type="color" value="#3b82f6" class="w-20 h-10 rounded border">
                        </div>
                        <div>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" checked class="w-4 h-4">
                                <span class="text-sm">Enable Dark Mode</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Other tabs (hidden by default) -->
        <div class="tab-panel hidden">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
                <!-- Add email, phone, address, etc. -->
            </div>
        </div>

        <div class="tab-panel hidden">Payment Settings Content...</div>
        <div class="tab-panel hidden">Shipping Settings Content...</div>
        <div class="tab-panel hidden">SEO Settings Content...</div>
    </div>

    <!-- Save Button -->
    <div class="mt-8 flex justify-end">
        <button onclick="saveSettings()" 
                class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg flex items-center gap-2">
            <i class="fas fa-save"></i>
            Save All Changes
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Tab Switching
    function switchTab(n) {
        document.querySelectorAll('.tab-button').forEach((btn, i) => {
            if (i === n) {
                btn.classList.add('active', 'border-b-2', 'border-blue-600', 'text-blue-600');
            } else {
                btn.classList.remove('active', 'border-b-2', 'border-blue-600', 'text-blue-600');
            }
        });

        document.querySelectorAll('.tab-panel').forEach((panel, i) => {
            panel.classList.toggle('hidden', i !== n);
        });
    }

    function saveSettings() {
        alert('✅ Settings saved successfully!');
        // You can later send via fetch() to backend
    }

    // Set first tab active by default
    document.addEventListener('DOMContentLoaded', () => {
        switchTab(0);
    });
</script>
@endpush