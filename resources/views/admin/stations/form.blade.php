@extends('layouts.admin')
@section('title', $station->exists ? 'Edit Stasiun' : 'Tambah Stasiun')

@section('content')
<div class="max-w-lg bg-white rounded-xl shadow-sm p-6">
    <form method="POST" action="{{ $station->exists ? route('admin.stations.update', $station) : route('admin.stations.store') }}">
        @csrf
        @if ($station->exists) @method('PUT') @endif

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Stasiun</label>
                <input type="text" name="name" value="{{ old('name', $station->name) }}" required
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode</label>
                <input type="text" name="code" value="{{ old('code', $station->code) }}" required
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                <input type="text" name="city" value="{{ old('city', $station->city) }}" required
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                    <input type="number" step="any" name="latitude" value="{{ old('latitude', $station->latitude) }}"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                    <input type="number" step="any" name="longitude" value="{{ old('longitude', $station->longitude) }}"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $station->is_active ?? true) ? 'checked' : '' }} class="rounded">
                <label for="is_active" class="text-sm text-gray-700">Aktif</label>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">Simpan</button>
            <a href="{{ route('admin.stations.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-5 py-2.5">Batal</a>
        </div>
    </form>
</div>
@endsection
