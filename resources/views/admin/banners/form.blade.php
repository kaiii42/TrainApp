@extends('layouts.admin')
@section('title', $banner->exists ? 'Edit Banner' : 'Tambah Banner')

@section('content')
<div class="max-w-lg bg-white rounded-xl shadow-sm p-6">
    <form method="POST" action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}">
        @csrf
        @if ($banner->exists) @method('PUT') @endif

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                <input type="text" name="title" value="{{ old('title', $banner->title) }}" required
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $banner->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar</label>
                <input type="url" name="image" value="{{ old('image', $banner->image) }}"
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Link</label>
                <input type="url" name="link" value="{{ old('link', $banner->link) }}"
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Diskon (%)</label>
                    <input type="number" name="discount_percentage" value="{{ old('discount_percentage', $banner->discount_percentage) }}" min="0" max="100"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                    <input type="number" name="order" value="{{ old('order', $banner->order ?? 0) }}"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mulai</label>
                    <input type="date" name="start_date" value="{{ old('start_date', optional($banner->start_date)->format('Y-m-d')) }}"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Selesai</label>
                    <input type="date" name="end_date" value="{{ old('end_date', optional($banner->end_date)->format('Y-m-d')) }}"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }} class="rounded">
                <label for="is_active" class="text-sm text-gray-700">Aktif</label>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">Simpan</button>
            <a href="{{ route('admin.banners.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-5 py-2.5">Batal</a>
        </div>
    </form>
</div>
@endsection
