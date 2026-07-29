@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

{{-- Top stat cards --}}
<div class="grid grid-cols-4 gap-4 mb-4">
    <div class="rounded-xl px-5 py-4 text-white flex items-center justify-between" style="background:linear-gradient(135deg,#7c3aed,#6d28d9)">
        <div>
            <p class="text-2xl font-bold">{{ $userCount }}</p>
            <p class="text-xs mt-0.5 opacity-80">Total Pengguna</p>
        </div>
        <span class="material-icons text-4xl opacity-40">group</span>
    </div>
    <div class="rounded-xl px-5 py-4 text-white flex items-center justify-between" style="background:linear-gradient(135deg,#059669,#047857)">
        <div>
            <p class="text-2xl font-bold">{{ $transactionCount }}</p>
            <p class="text-xs mt-0.5 opacity-80">Total Transaksi</p>
        </div>
        <span class="material-icons text-4xl opacity-40">receipt_long</span>
    </div>
    <div class="rounded-xl px-5 py-4 text-white flex items-center justify-between" style="background:linear-gradient(135deg,#d97706,#b45309)">
        <div>
            <p class="text-2xl font-bold">{{ $pendingCount }}</p>
            <p class="text-xs mt-0.5 opacity-80">Pending</p>
        </div>
        <span class="material-icons text-4xl opacity-40">pending</span>
    </div>
    <div class="rounded-xl px-5 py-4 text-white flex items-center justify-between" style="background:linear-gradient(135deg,#dc2626,#b91c1c)">
        <div>
            <p class="text-2xl font-bold">Rp {{ number_format($totalRevenue/1000000, 1, '.', '') }}jt</p>
            <p class="text-xs mt-0.5 opacity-80">Total Pendapatan</p>
        </div>
        <span class="material-icons text-4xl opacity-40">payments</span>
    </div>
</div>

{{-- Secondary stat cards --}}
<div class="grid grid-cols-4 gap-4 mb-4">
    <div class="bg-white rounded-xl py-4 px-5 flex flex-col items-center shadow-sm">
        <span class="material-icons text-3xl text-blue-500 mb-1.5">train</span>
        <p class="text-xl font-bold text-gray-800">{{ $trainCount }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Kereta Aktif</p>
    </div>
    <div class="bg-white rounded-xl py-4 px-5 flex flex-col items-center shadow-sm">
        <span class="material-icons text-3xl text-green-500 mb-1.5">location_on</span>
        <p class="text-xl font-bold text-gray-800">{{ $stationCount }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Stasiun Aktif</p>
    </div>
    <div class="bg-white rounded-xl py-4 px-5 flex flex-col items-center shadow-sm">
        <span class="material-icons text-3xl text-purple-500 mb-1.5">calendar_month</span>
        <p class="text-xl font-bold text-gray-800">{{ $scheduleCount }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Jadwal Aktif</p>
    </div>
    <div class="bg-white rounded-xl py-4 px-5 flex flex-col items-center shadow-sm">
        <span class="material-icons text-3xl text-orange-400 mb-1.5">shopping_cart</span>
        <p class="text-xl font-bold text-gray-800">{{ $todayCount }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Transaksi Hari Ini</p>
    </div>
</div>

{{-- Charts row --}}
<div class="grid grid-cols-3 gap-5">
    {{-- Monthly Revenue Bar Chart --}}
    <div class="col-span-2 bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/>
            </svg>
            <h3 class="font-semibold text-gray-700 text-sm">Pendapatan Bulanan {{ $year }}</h3>
        </div>
        <canvas id="revenueChart" height="120"></canvas>
    </div>

    {{-- Status Donut Chart --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/>
            </svg>
            <h3 class="font-semibold text-gray-700 text-sm">Status Transaksi</h3>
        </div>
        <canvas id="statusChart" height="180"></canvas>
        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-4 text-xs text-gray-500">
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#f59e0b"></span>Menunggu</span>
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#3b82f6"></span>Dibayar</span>
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#ef4444"></span>Dibatalkan</span>
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#10b981"></span>Selesai</span>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
const revenueData = @json($revenueData);

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Pendapatan',
            data: revenueData,
            backgroundColor: 'rgba(109,40,217,0.15)',
            borderColor: '#7c3aed',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                ticks: {
                    callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'jt',
                    font: { size: 11 },
                },
                grid: { color: '#f3f4f6' },
            },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});

const statusCounts = @json($statusCounts);
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Menunggu','Dibayar','Dibatalkan','Selesai'],
        datasets: [{
            data: [
                statusCounts.pending   ?? 0,
                statusCounts.paid      ?? 0,
                statusCounts.cancelled ?? 0,
                statusCounts.completed ?? 0,
            ],
            backgroundColor: ['#f59e0b','#3b82f6','#ef4444','#10b981'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: { legend: { display: false } }
    }
});
</script>
@endsection
