@extends('layouts.admin')
@section('title', 'Transaksi')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Kode</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">User</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Rute</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Tanggal</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Total</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($transactions as $trx)
            @php
                $statusColor = match($trx->status) {
                    'paid'      => 'bg-green-100 text-green-700',
                    'pending'   => 'bg-yellow-100 text-yellow-700',
                    'cancelled' => 'bg-red-100 text-red-700',
                    default     => 'bg-blue-100 text-blue-700',
                };
            @endphp
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3 font-mono text-xs text-gray-600">{{ $trx->booking_code ?? $trx->id }}</td>
                <td class="px-6 py-3 text-gray-800">{{ $trx->user->name }}</td>
                <td class="px-6 py-3 text-gray-600">
                    {{ $trx->schedule->originStation->code ?? '-' }} → {{ $trx->schedule->destinationStation->code ?? '-' }}
                </td>
                <td class="px-6 py-3 text-gray-600 text-xs">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-6 py-3 text-gray-600">Rp{{ number_format($trx->total_price, 0, ',', '.') }}</td>
                <td class="px-6 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">{{ ucfirst($trx->status) }}</span>
                </td>
                <td class="px-6 py-3 text-right">
                    <a href="{{ route('admin.transactions.show', $trx) }}" class="text-blue-600 hover:underline">Detail</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-6 py-6 text-center text-gray-400">Belum ada transaksi.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-3 border-t">{{ $transactions->links() }}</div>
</div>
@endsection
