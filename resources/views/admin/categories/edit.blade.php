@extends('layouts.admin')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Category</h1>
            <p class="text-gray-600">Update category details</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Category Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                               class="form-input" placeholder="Enter category name">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Image -->
                    @if($category->image)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                        <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-32 h-20 object-cover rounded-lg">
                    </div>
                    @endif

                    <!-- Category Image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Category Image</label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="form-input">
                        <p class="mt-1 text-sm text-gray-500">Leave empty to keep current image. Recommended size: 300x200 pixels</p>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0"
                               class="form-input" placeholder="0">
                        <p class="mt-1 text-sm text-gray-500">Lower numbers appear first in the menu</p>
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t">
                    <a href="{{ route('admin.categories.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
