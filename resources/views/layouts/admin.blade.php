<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — TrainApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-100">

    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 w-56 flex flex-col text-white z-30" style="background:linear-gradient(180deg,#6d28d9 0%,#5b21b6 100%)">
        <div class="px-6 py-5 border-b border-white/10">
            <div class="flex items-center gap-2">
                <span class="material-icons text-xl">train</span>
                <div>
                    <p class="font-bold text-base leading-tight">TrainApp</p>
                    <p class="text-xs text-white/60">Admin Dashboard</p>
                </div>
            </div>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-0.5 text-sm">
            @php
                $nav = [
                    ['route' => 'admin.dashboard',          'label' => 'Dashboard',    'icon' => 'dashboard'],
                    ['route' => 'admin.stations.index',     'label' => 'Stasiun',      'icon' => 'location_on'],
                    ['route' => 'admin.trains.index',       'label' => 'Kereta',       'icon' => 'train'],
                    ['route' => 'admin.schedules.index',    'label' => 'Jadwal',       'icon' => 'calendar_month'],
                    ['route' => 'admin.banners.index',      'label' => 'Banner Promo', 'icon' => 'image'],
                    ['route' => 'admin.transactions.index', 'label' => 'Transaksi',    'icon' => 'receipt_long'],
                    ['route' => 'admin.users.index',        'label' => 'Pengguna',     'icon' => 'group'],
                ];
            @endphp
            @foreach ($nav as $item)
                @php $active = request()->routeIs($item['route'].'*'); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ $active ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                    <span class="material-icons text-[18px] flex-shrink-0">{{ $item['icon'] }}</span>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
        <div class="px-3 py-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 transition text-sm text-white/70 hover:text-white">
                    <span class="material-icons text-[18px]">logout</span>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="ml-56 flex flex-col min-h-screen">
        <header class="sticky top-0 z-20 bg-white shadow-sm px-8 py-4 flex items-center justify-between">
            <h1 class="text-lg font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold" style="background:#7c3aed">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        <main class="flex-1 px-8 py-6">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
