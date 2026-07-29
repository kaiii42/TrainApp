@extends('layouts.admin')
@section('title', 'Jadwal')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-base font-medium text-gray-700">Daftar Jadwal</h2>
    <a href="{{ route('admin.schedules.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">+ Tambah</a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Kereta</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Rute</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Berangkat</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Tiba</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Harga</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($schedules as $schedule)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3 font-medium text-gray-800">{{ $schedule->train->name }}</td>
                <td class="px-6 py-3 text-gray-600">{{ $schedule->originStation->code }} → {{ $schedule->destinationStation->code }}</td>
                <td class="px-6 py-3 text-gray-600">{{ $schedule->departure_time }}</td>
                <td class="px-6 py-3 text-gray-600">{{ $schedule->arrival_time }}</td>
                <td class="px-6 py-3 text-gray-600">Rp{{ number_format($schedule->price, 0, ',', '.') }}</td>
                <td class="px-6 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $schedule->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $schedule->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-6 py-3 flex gap-3 justify-end">
                    <a href="{{ route('admin.schedules.edit', $schedule) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" onsubmit="return confirm('Hapus jadwal ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-6 py-6 text-center text-gray-400">Belum ada jadwal.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-3 border-t">{{ $schedules->links() }}</div>
</div>
@endsection
