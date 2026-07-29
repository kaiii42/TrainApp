@extends('layouts.admin')
@section('title', 'Kereta')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-base font-medium text-gray-700">Daftar Kereta</h2>
    <a href="{{ route('admin.trains.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">+ Tambah</a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Nama</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Nomor</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Kelas</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Kapasitas</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($trains as $train)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3 font-medium text-gray-800">{{ $train->name }}</td>
                <td class="px-6 py-3 text-gray-600">{{ $train->train_number }}</td>
                <td class="px-6 py-3 text-gray-600 capitalize">{{ $train->class }}</td>
                <td class="px-6 py-3 text-gray-600">{{ $train->capacity }}</td>
                <td class="px-6 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $train->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $train->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-6 py-3 flex gap-3 justify-end">
                    <a href="{{ route('admin.trains.edit', $train) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('admin.trains.destroy', $train) }}" onsubmit="return confirm('Hapus kereta ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-6 text-center text-gray-400">Belum ada kereta.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-3 border-t">{{ $trains->links() }}</div>
</div>
@endsection
