@extends('layouts.admin')
@section('title', $schedule->exists ? 'Edit Jadwal' : 'Tambah Jadwal')

@section('content')
<div class="max-w-xl bg-white rounded-xl shadow-sm p-6">
    <form method="POST" action="{{ $schedule->exists ? route('admin.schedules.update', $schedule) : route('admin.schedules.store') }}">
        @csrf
        @if ($schedule->exists) @method('PUT') @endif

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kereta</label>
                <select name="train_id" required class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih kereta</option>
                    @foreach ($trains as $train)
                        <option value="{{ $train->id }}" {{ old('train_id', $schedule->train_id) == $train->id ? 'selected' : '' }}>
                            {{ $train->name }} ({{ $train->train_number }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stasiun Asal</label>
                    <select name="origin_station_id" required class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih stasiun</option>
                        @foreach ($stations as $station)
                            <option value="{{ $station->id }}" {{ old('origin_station_id', $schedule->origin_station_id) == $station->id ? 'selected' : '' }}>
                                {{ $station->name }} ({{ $station->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stasiun Tujuan</label>
                    <select name="destination_station_id" required class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih stasiun</option>
                        @foreach ($stations as $station)
                            <option value="{{ $station->id }}" {{ old('destination_station_id', $schedule->destination_station_id) == $station->id ? 'selected' : '' }}>
                                {{ $station->name }} ({{ $station->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Berangkat</label>
                    <input type="time" name="departure_time" value="{{ old('departure_time', $schedule->departure_time) }}" required
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tiba</label>
                    <input type="time" name="arrival_time" value="{{ old('arrival_time', $schedule->arrival_time) }}" required
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Durasi</label>
                    <input type="text" name="duration" placeholder="2j 30m" value="{{ old('duration', $schedule->duration) }}" required
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $schedule->price) }}" required min="0"
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hari Tersedia</label>
                <div class="flex flex-wrap gap-3">
                    @php
                        $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                        $selectedDays = old('available_days', $schedule->available_days ?? []);
                    @endphp
                    @foreach ($days as $day)
                        <label class="flex items-center gap-1.5 text-sm">
                            <input type="checkbox" name="available_days[]" value="{{ $day }}"
                                {{ in_array($day, $selectedDays) ? 'checked' : '' }} class="rounded">
                            {{ $day }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $schedule->is_active ?? true) ? 'checked' : '' }} class="rounded">
                <label for="is_active" class="text-sm text-gray-700">Aktif</label>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">Simpan</button>
            <a href="{{ route('admin.schedules.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-5 py-2.5">Batal</a>
        </div>
    </form>
</div>
@endsection
