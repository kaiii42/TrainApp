@extends('layouts.admin')
@section('title', 'Promo Banner')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-base font-medium text-gray-700">Daftar Banner</h2>
    <a href="{{ route('admin.banners.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">+ Tambah</a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Urutan</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Judul</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Diskon</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Periode</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($banners as $banner)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3 text-gray-600">{{ $banner->order }}</td>
                <td class="px-6 py-3 font-medium text-gray-800">{{ $banner->title }}</td>
                <td class="px-6 py-3 text-gray-600">{{ $banner->discount_percentage ? $banner->discount_percentage.'%' : '-' }}</td>
                <td class="px-6 py-3 text-gray-600 text-xs">
                    {{ $banner->start_date ? $banner->start_date->format('d/m/Y') : '-' }}
                    @if ($banner->end_date) — {{ $banner->end_date->format('d/m/Y') }} @endif
                </td>
                <td class="px-6 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $banner->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-6 py-3 flex gap-3 justify-end">
                    <a href="{{ route('admin.banners.edit', $banner) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" onsubmit="return confirm('Hapus banner ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-6 text-center text-gray-400">Belum ada banner.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-3 border-t">{{ $banners->links() }}</div>
</div>
@endsection
