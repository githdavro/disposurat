<!-- notifikasi-popup.blade.php -->
<div 
    x-data="{ open: false }" 
    @keydown.escape.window="open = false" 
    x-show="open" 
    x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
>
    <div 
        class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 overflow-y-auto max-h-[80vh]"
        @click.away="open = false"
    >
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Notifikasi</h2>
            <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Tombol Tandai Semua -->
        @if($notifikasis->where('dibaca', false)->count() > 0)
            <form action="{{ route('notifikasi.read-all') }}" method="POST" class="mb-4">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition text-sm w-full">
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif

        <!-- List Notifikasi -->
        <div class="space-y-4">
            @forelse($notifikasis as $notifikasi)
                <div class="border border-gray-200 rounded-lg p-4 {{ $notifikasi->dibaca ? 'bg-gray-50' : 'bg-white border-l-4 border-l-blue-500' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900 {{ $notifikasi->dibaca ? '' : 'font-semibold' }}">
                                {{ $notifikasi->judul }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $notifikasi->pesan }}</p>
                            <p class="text-xs text-gray-500 mt-2">
                                {{ $notifikasi->created_at->translatedFormat('d F Y H:i') }} 
                                ({{ $notifikasi->created_at->diffForHumans() }})
                            </p>
                        </div>
                        <div class="flex space-x-2 ml-4">
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
                <div class="text-center py-8">
                    <i class="fas fa-bell-slash text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Tidak ada notifikasi</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifikasis->hasPages())
            <div class="mt-6">
                {{ $notifikasis->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Tombol untuk memanggil popup -->
<button @click="$refs.notifikasiModal.__x.$data.open = true" class="bg-blue-600 text-white px-4 py-2 rounded-md">
    Lihat Notifikasi
</button>
