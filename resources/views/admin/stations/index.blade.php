@extends('layouts.admin')
@section('title', 'Stasiun')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-base font-medium text-gray-700">Daftar Stasiun</h2>
    <a href="{{ route('admin.stations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">+ Tambah</a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Nama</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Kode</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Kota</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($stations as $station)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3 font-medium text-gray-800">{{ $station->name }}</td>
                <td class="px-6 py-3 text-gray-600">{{ $station->code }}</td>
                <td class="px-6 py-3 text-gray-600">{{ $station->city }}</td>
                <td class="px-6 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $station->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $station->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-6 py-3 flex gap-3 justify-end">
                    <a href="{{ route('admin.stations.edit', $station) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('admin.stations.destroy', $station) }}" onsubmit="return confirm('Hapus stasiun ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-6 text-center text-gray-400">Belum ada stasiun.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-3 border-t">{{ $stations->links() }}</div>
</div>
@endsection
