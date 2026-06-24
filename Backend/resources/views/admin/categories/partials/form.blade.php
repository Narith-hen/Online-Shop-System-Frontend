@if ($errors->any())
    <div class="p-4 bg-red-100 text-red-700 border border-red-300 rounded-lg">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-6">

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Category Name <span
                class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            required>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
        <textarea name="description" rows="4"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $category->description ?? '') }}</textarea>
    </div>

    <label class="inline-flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))
            class="rounded border-gray-300 text-blue-600">
        <span class="text-sm font-medium text-gray-700">Active</span>
    </label>

</div>
