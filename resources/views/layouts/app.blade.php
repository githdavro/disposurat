<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Surat Management</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logos/dispo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <div class="flex items-center">
                        <!-- Tombol hamburger (hanya tampil di layar kecil) -->
                        <button id="sidebarToggle" aria-label="Toggle sidebar" aria-expanded="false" class="md:hidden text-gray-600 hover:text-gray-900 mr-3">
                            <i class="fas fa-bars text-xl"></i>
                        </button>

                        <h1 class="text-2xl font-semibold text-green-900">
                            @yield('header')
                        </h1>
                    </div>


                    <div class="flex items-center space-x-4 relative">
       
                        <div x-data="{ openNotifikasi: false }" class="relative">
                            <button @click="openNotifikasi = !openNotifikasi" class="relative text-gray-600 hover:text-gray-900">
                                <i class="fas fa-bell text-xl"></i>
                                @php
                                    $unreadCount = auth()->user()->notifikasis->where('dibaca', false)->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </button>

                            <!-- Popup Notifikasi -->
                            <div 
                                x-show="openNotifikasi"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                @click.away="openNotifikasi = false"
                                class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg border z-50 max-h-[32rem] overflow-y-auto">
                                
                                <div class="p-4 flex justify-between items-center border-b">
                                    <h3 class="font-semibold text-gray-800">Notifikasi</h3>
                                    @if($unreadCount > 0)
                                        <form action="{{ route('notifikasi.read-all') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">
                                                Tandai Semua Dibaca
                                            </button>
                                            
                                        </form>
                                    @endif
                                </div>

                                <div class="space-y-2 p-2">
                                    @forelse(auth()->user()->notifikasis->sortByDesc('created_at') as $notifikasi)
                                        <div class="border border-gray-200 rounded-lg p-3 {{ $notifikasi->dibaca ? 'bg-gray-50' : 'bg-white border-l-4 border-l-blue-500' }}">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <h4 class="{{ $notifikasi->dibaca ? 'font-medium' : 'font-semibold' }} text-gray-900">{{ $notifikasi->judul }}</h4>
                                                    <p class="text-sm text-gray-600 mt-1">{{ $notifikasi->pesan }}</p>
                                                    <p class="text-xs text-gray-400 mt-1">
                                                        {{ $notifikasi->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                                <div class="flex flex-col ml-2 space-y-1">
                                                    @if(!$notifikasi->dibaca)
                                                        <form action="{{ route('notifikasi.read', $notifikasi->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="text-green-600 hover:text-green-800 text-sm" title="Tandai dibaca">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if($notifikasi->link)
                                                        <a href="{{ $notifikasi->link }}" class="text-blue-600 hover:text-blue-800 text-sm" title="Lihat detail">
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center text-gray-500 py-4">
                                            <i class="fas fa-bell-slash text-lg mb-2"></i>
                                            Tidak ada notifikasi
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>

                       <!-- User info -->
                        <div class="flex flex-col">
                            <p class="text-sm font-medium text-gray-900 uppercase">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-xs text-gray-500 text-right uppercase">
                                {{ Auth::user()->roles->pluck('name')->join(', ') }}
                            </p>
                        </div>


                        <!-- Avatar Profil -->
                        <div class="relative" x-data="{ openProfile: false }">
                            <button @click="openProfile = !openProfile" class="flex items-center space-x-2 focus:outline-none">
                                <img src="{{ asset('images/icons/user.png') }}" 
                                    alt="Avatar" 
                                    class="w-10 h-10 rounded-full shadow-lg object-cover">
                            </button>

                            <div x-show="openProfile"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                @click.away="openProfile = false"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border z-50 transform origin-top-right"
                            >
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="py-3 px-2 bg-white rounded-lg shadow-md space-y-2">
                                    <a href="{{ route('profile.edit') }}" 
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-2 rounded-md">
                                        <i class="fas fa-user"></i>
                                        <span>Profile</span>
                                    </a>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-100 hover:text-red-700 flex items-center space-x-2 rounded-md">
                                            <i class="fas fa-sign-out-alt"></i>
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggle');
    const closeBtn = document.getElementById('sidebarClose');

    function isMdUp() {
        return window.matchMedia('(min-width: 768px)').matches;
    }

    function openSidebar() {
        sidebar?.classList.remove('-translate-x-full');
        overlay?.classList.remove('hidden');
        toggleBtn?.setAttribute('aria-expanded', 'true');
    }

    function closeSidebar() {
        if (isMdUp()) {
            sidebar?.classList.remove('-translate-x-full');
            overlay?.classList.add('hidden');
            toggleBtn?.setAttribute('aria-expanded', 'false');
            return;
        }
        sidebar?.classList.add('-translate-x-full');
        overlay?.classList.add('hidden');
        toggleBtn?.setAttribute('aria-expanded', 'false');
    }

    toggleBtn?.addEventListener('click', () => {
        sidebar?.classList.contains('-translate-x-full') ? openSidebar() : closeSidebar();
    });

    closeBtn?.addEventListener('click', closeSidebar);
    overlay?.addEventListener('click', closeSidebar);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });
    window.addEventListener('resize', () => {
        if (isMdUp()) {
            sidebar?.classList.remove('-translate-x-full');
            overlay?.classList.add('hidden');
        } else overlay?.classList.add('hidden');
    });
});
</script>

</body>
</html>
