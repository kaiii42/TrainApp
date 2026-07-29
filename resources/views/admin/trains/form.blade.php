@extends('layouts.admin')
@section('title', $train->exists ? 'Edit Kereta' : 'Tambah Kereta')

@section('content')
<div class="max-w-lg bg-white rounded-xl shadow-sm p-6">
    <form method="POST" action="{{ $train->exists ? route('admin.trains.update', $train) : route('admin.trains.store') }}">
        @csrf
        @if ($train->exists) @method('PUT') @endif

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kereta</label>
                <input type="text" name="name" value="{{ old('name', $train->name) }}" required
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Kereta</label>
                <input type="text" name="train_number" value="{{ old('train_number', $train->train_number) }}" required
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                <select name="class" required class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach (['ekonomi', 'bisnis', 'eksekutif'] as $kelas)
                        <option value="{{ $kelas }}" {{ old('class', $train->class) === $kelas ? 'selected' : '' }}>{{ ucfirst($kelas) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas</label>
                <input type="number" name="capacity" value="{{ old('capacity', $train->capacity) }}" required min="1"
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $train->is_active ?? true) ? 'checked' : '' }} class="rounded">
                <label for="is_active" class="text-sm text-gray-700">Aktif</label>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">Simpan</button>
            <a href="{{ route('admin.trains.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-5 py-2.5">Batal</a>
        </div>
    </form>
</div>
@endsection
