@extends('layouts.admin')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Product</h1>
            <p class="text-gray-600">Update product details</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select name="category_id" id="category_id" required class="form-input">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                               class="form-input" placeholder="Enter product name">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="3" class="form-input" 
                                  placeholder="Describe the product...">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (₹) *</label>
                        <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required
                               class="form-input" placeholder="0.00">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Image -->
                    @if($product->image)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-32 h-24 object-cover rounded-lg">
                    </div>
                    @endif

                    <!-- Product Image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="form-input">
                        <p class="mt-1 text-sm text-gray-500">Leave empty to keep current image. Recommended size: 400x300 pixels</p>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Veg/Non-Veg -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="is_veg" value="1" {{ old('is_veg', $product->is_veg) == '1' ? 'checked' : '' }}
                                       class="text-green-600 focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">Vegetarian</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="is_veg" value="0" {{ old('is_veg', $product->is_veg) == '0' ? 'checked' : '' }}
                                       class="text-red-600 focus:ring-red-500">
                                <span class="ml-2 text-sm text-gray-700">Non-Vegetarian</span>
                            </label>
                        </div>
                        @error('is_veg')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Spice Level -->
                    <div>
                        <label for="spice_level" class="block text-sm font-medium text-gray-700 mb-2">Spice Level *</label>
                        <select name="spice_level" id="spice_level" required class="form-input">
                            <option value="mild" {{ old('spice_level', $product->spice_level) == 'mild' ? 'selected' : '' }}>Mild</option>
                            <option value="medium" {{ old('spice_level', $product->spice_level) == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="hot" {{ old('spice_level', $product->spice_level) == 'hot' ? 'selected' : '' }}>Hot</option>
                        </select>
                        @error('spice_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preparation Time -->
                    <div>
                        <label for="prep_time" class="block text-sm font-medium text-gray-700 mb-2">Preparation Time (minutes) *</label>
                        <input type="number" name="prep_time" id="prep_time" min="1" max="120" value="{{ old('prep_time', $product->prep_time) }}" required
                               class="form-input" placeholder="15">
                        @error('prep_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t">
                    <a href="{{ route('admin.products.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
