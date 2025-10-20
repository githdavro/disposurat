<div id="sidebar" 
    class="bg-white text-gray-800 w-64 flex flex-col space-y-6 py-6 px-4 
           absolute inset-y-0 left-0 transform -translate-x-full 
           md:translate-x-0 transition duration-300 ease-in-out 
           md:relative md:translate-x-0 z-40 shadow-lg">
    
    <button id="sidebarClose" aria-label="Tutup Sidebar" 
        class="md:hidden absolute top-3 right-3 text-gray-600 hover:text-gray-900 
               hover:bg-gray-200 rounded-full p-2 transition">
        <i class="fas fa-times"></i>
    </button>

    <!-- Logo -->
    <div class="flex items-center space-x-3 px-2">
    <img
        src="{{ asset('images/logos/dispo.png') }}"
        alt="logo dispo surat"
        class="max-w-full max-h-[50px] select-none"
        draggable="false"
    />
    <span class="text-2xl font-extrabold tracking-wide text-gray-700">Dispo Surat</span>
</div>

    <nav class="flex flex-col space-y-3 mt-4">
        
            <a href="{{ route('unit.create-surat') }}" 
            class="block py-3 px-4 rounded-lg font-medium text-white text-center
                    bg-gradient-to-r from-green-500 to-emerald-600 
                    hover:from-green-600 hover:to-emerald-700 
                    transition duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-plus mr-2"></i>Buat Surat
            </a>
        
        <!-- Navigasi -->
        
        <div class="pt-3 border-t border-gray-200 mt-3 space-y-2">
            <a href="{{ route('dashboard') }}" 
               class="block py-3 px-4 rounded-md transition duration-200 
                      hover:bg-gray-100 hover:text-gray-900 
                      {{ request()->routeIs('dashboard') ? 'bg-green-100 font-medium text-green-700' : 'text-gray-700' }}">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>

            <a href="{{ route('notifikasi.index') }}" 
               class="block py-3 px-4 rounded-md transition duration-200 
                      hover:bg-gray-100 hover:text-gray-900 
                      {{ request()->routeIs('notifikasi.index') ? 'bg-green-100 font-medium text-green-900' : 'text-gray-700' }}">
                <i class="fas fa-bell mr-2"></i>Notifikasi
            </a>
        </div>
        

        @if(Auth::user()->hasRole('unit'))
        <!-- Menu Unit -->
        <div class="pt-3 border-t border-gray-200 mt-3 space-y-2">
            <a href="{{ route('unit.sent') }}" 
               class="block py-3 px-4 rounded-md transition duration-200 
                      hover:bg-gray-100 hover:text-gray-900 
                      {{ request()->routeIs('unit.sent') ? 'bg-green-100 font-semibold text-green-900' : 'text-gray-700' }}">
                <i class="fas fa-paper-plane mr-2"></i>Surat Terkirim
            </a>
            <a href="{{ route('unit.inbox') }}" 
               class="block py-3 px-4 rounded-md transition duration-200 
                      hover:bg-gray-100 hover:text-gray-900 
                      {{ request()->routeIs('unit.inbox') ? 'bg-green-100 font-semibold text-green-900' : 'text-gray-700' }}">
                <i class="fas fa-inbox mr-2"></i>Surat Masuk
            </a>
        </div>
        @endif

        @if(Auth::user()->hasRole('pengadaan'))
        <!-- Menu Pengadaan -->
        <div class="pt-3 border-t border-gray-200 mt-3 space-y-2">
            <a href="{{ route('pengadaan.inbox') }}" 
               class="block py-3 px-4 rounded-md transition duration-200 
                      hover:bg-gray-100 hover:text-gray-900 
                      {{ request()->routeIs('pengadaan.inbox') ? 'bg-green-100 font-semibold text-green-900' : 'text-gray-700' }}">
                <i class="fas fa-inbox mr-2"></i>Inbox Pengadaan
            </a>
        </div>
        @endif

        @if(Auth::user()->hasRole('direktur'))
        <!-- Menu Direktur -->
        <div class="pt-3 border-t border-gray-200 mt-3 space-y-2">
            <a href="{{ route('direktur.review') }}" 
               class="block py-3 px-4 rounded-md transition duration-200 
                      hover:bg-gray-100 hover:text-gray-900 
                      {{ request()->routeIs('direktur.review') ? 'bg-green-100 font-semibold text-green-900' : 'text-gray-700' }}">
                <i class="fas fa-clipboard-check mr-2"></i>Review Surat
            </a>
            <a href="{{ route('direktur.arsip') }}" 
               class="block py-3 px-4 rounded-md transition duration-200 
                      hover:bg-gray-100 hover:text-gray-900 
                      {{ request()->routeIs('direktur.arsip') ? 'bg-gray-100 font-semibold text-gray-900' : 'text-gray-700' }}">
                <i class="fas fa-archive mr-2"></i>Arsip Surat
            </a>
        </div>
        @endif

        @if(Auth::user()->hasRole('admin'))
        <!-- Menu untuk Admin -->
        <div class="pt-3 border-t border-gray-200 mt-3 space-y-2">
            <a href="{{ route('admin.dashboard') }}" 
               class="block py-3 px-4 rounded-md transition duration-200 
                      hover:bg-gray-100 hover:text-gray-900 
                      {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 font-semibold text-gray-900' : 'text-gray-700' }}">
                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Admin
            </a>
            <a href="{{ route('admin.users') }}" 
               class="block py-3 px-4 rounded-md transition duration-200 
                      hover:bg-gray-100 hover:text-gray-900 
                      {{ request()->routeIs('admin.users') ? 'bg-gray-100 font-semibold text-gray-900' : 'text-gray-700' }}">
                <i class="fas fa-users-cog mr-2"></i>Manajemen User
            </a>
            <a href="{{ route('admin.units') }}" 
               class="block py-3 px-4 rounded-md transition duration-200 
                      hover:bg-gray-100 hover:text-gray-900 
                      {{ request()->routeIs('admin.units') ? 'bg-gray-100 font-semibold text-gray-900' : 'text-gray-700' }}">
                <i class="fas fa-building mr-2"></i>Manajemen Unit
            </a>
            <a href="{{ route('admin.logs') }}" 
               class="block py-3 px-4 rounded-md transition duration-200 
                      hover:bg-gray-100 hover:text-gray-900 
                      {{ request()->routeIs('admin.logs') ? 'bg-gray-100 font-semibold text-gray-900' : 'text-gray-700' }}">
                <i class="fas fa-clipboard-list mr-2"></i>System Logs
            </a>
        </div>
        @endif

    </nav>
</div>

<!-- Overlay Mobile -->
<div id="sidebarOverlay" 
     class="hidden md:hidden fixed inset-0 bg-black bg-opacity-50 z-30 transition-opacity"></div>
