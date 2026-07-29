@extends('layouts.admin')
@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-xl space-y-6">
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-3 text-sm">
        <h2 class="font-semibold text-gray-800 text-base mb-4">Info Transaksi</h2>
        <div class="grid grid-cols-2 gap-y-3">
            <span class="text-gray-500">Kode Booking</span>
            <span class="font-mono text-gray-800">{{ $transaction->booking_code ?? $transaction->id }}</span>
            <span class="text-gray-500">User</span>
            <span class="text-gray-800">{{ $transaction->user->name }} ({{ $transaction->user->email }})</span>
            <span class="text-gray-500">Kereta</span>
            <span class="text-gray-800">{{ $transaction->schedule->train->name }}</span>
            <span class="text-gray-500">Rute</span>
            <span class="text-gray-800">{{ $transaction->schedule->originStation->name }} → {{ $transaction->schedule->destinationStation->name }}</span>
            <span class="text-gray-500">Berangkat</span>
            <span class="text-gray-800">{{ $transaction->schedule->departure_time }}</span>
            <span class="text-gray-500">Total</span>
            <span class="text-gray-800 font-semibold">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</span>
            <span class="text-gray-500">Tanggal</span>
            <span class="text-gray-800">{{ $transaction->created_at->format('d M Y H:i') }}</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="font-semibold text-gray-800 text-base mb-4">Update Status</h2>
        <form method="POST" action="{{ route('admin.transactions.update-status', $transaction) }}" class="flex gap-3 items-end">
            @csrf @method('PATCH')
            <div class="flex-1">
                <select name="status" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach (['pending', 'paid', 'cancelled', 'completed'] as $s)
                        <option value="{{ $s }}" {{ $transaction->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">Update</button>
        </form>
    </div>

    <a href="{{ route('admin.transactions.index') }}" class="inline-block text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
</div>
@endsection
