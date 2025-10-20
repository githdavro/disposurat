@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('header', 'Dashboard Admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Statistik Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-envelope text-blue-500 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Surat</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $totalSurat }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-users text-green-500 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total User</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $totalUser }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-building text-yellow-500 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Unit</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $totalUnit }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-clock text-red-500 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Belum Diproses</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $suratBelumDiproses }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Chart Surat Per Bulan -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Surat Per Bulan ({{ date('Y') }})</h3>
        <div class="h-64">
            <canvas id="suratChart"></canvas>
        </div>
    </div>

    <!-- Distribusi User per Role -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi User per Role</h3>
        <div class="space-y-4">
            @foreach($userPerRole as $role)
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-700 capitalize">{{ $role->role_name }}</span>
                <div class="flex items-center">
                <div id="progressContainer" class="w-32 bg-gray-200 rounded-full h-2.5 mr-2 relative cursor-pointer">
                    <div id="progressBar" class="bg-blue-600 h-2.5 rounded-full" 
                        style="width: {{ ($role->total / $totalUser) * 100 }}%"></div>
                </div>
                <span id="progressText" class="text-sm text-gray-500">{{ $role->total }} user</span>
            </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
        <div class="space-y-3">
            <a href="{{ route('admin.users') }}" 
               class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                <i class="fas fa-users-cog text-blue-500 mr-3"></i>
                <span class="text-sm font-medium text-gray-700">Manajemen User</span>
            </a>
            <a href="{{ route('admin.units') }}" 
               class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                <i class="fas fa-building text-green-500 mr-3"></i>
                <span class="text-sm font-medium text-gray-700">Manajemen Unit</span>
            </a>
            <a href="{{ route('admin.logs') }}" 
               class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                <i class="fas fa-clipboard-list text-purple-500 mr-3"></i>
                <span class="text-sm font-medium text-gray-700">System Logs</span>
            </a>
            <a href="#" 
               class="flex items-center p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                <i class="fas fa-cog text-yellow-500 mr-3"></i>
                <span class="text-sm font-medium text-gray-700">Pengaturan Sistem</span>
            </a>
        </div>
    </div>

    <!-- Surat Tertunda -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Surat Perlu Perhatian</h3>
        <div class="space-y-4">
            @forelse($suratTertunda as $surat)
            <div class="flex items-start space-x-3 p-3 bg-yellow-50 rounded-lg">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $surat->perihal }}</p>
                    <p class="text-xs text-gray-500">
                        Dari: {{ $surat->pengirim->name ?? 'Tidak diketahui' }} • 
                        Status: {{ ucfirst(str_replace('_', ' ', $surat->status)) }} • 
                        {{ $surat->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
            @empty
            <div class="text-center py-4">
                <i class="fas fa-check-circle text-2xl text-green-300 mb-2"></i>
                <p class="text-sm text-gray-500">Tidak ada surat tertunda</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="mt-6 bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Perihal
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pengirim
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Waktu
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentSurat as $surat)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($surat->perihal, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $surat->pengirim->name ?? 'Tidak diketahui' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($surat->status == 'disetujui') bg-green-100 text-green-800
                            @elseif($surat->status == 'ditolak') bg-red-100 text-red-800
                            @elseif($surat->status == 'dikirim') bg-blue-100 text-blue-800
                            @elseif($surat->status == 'diterima_pengadaan') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $surat->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $surat->created_at->diffForHumans() }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                        Belum ada aktivitas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Informasi Sistem -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <i class="fas fa-server text-blue-400 text-xl"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Informasi Sistem</h3>
            <div class="mt-2 text-sm text-blue-700">
                <p class="mb-1"><strong>Role:</strong> {{ Auth::user()->getRoleNames()->first() }}</p>
                <p class="mb-1"><strong>Login Terakhir:</strong> {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : 'Pertama kali' }}</p>
                <p class="mb-1"><strong>Versi Aplikasi:</strong> 1.0.0</p>
                <p class="mb-1"><strong>Waktu Server:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('suratChart').getContext('2d');
        const suratChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Surat',
                    data: @json($suratChartData),
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>
<script>
const container = document.getElementById('progressContainer');
const bar = document.getElementById('progressBar');
const text = document.getElementById('progressText');

let isDragging = false;

container.addEventListener('mousedown', (e) => {
    isDragging = true;
    updateProgress(e);
});
document.addEventListener('mouseup', () => isDragging = false);
document.addEventListener('mousemove', (e) => {
    if (isDragging) updateProgress(e);
});

function updateProgress(e) {
    const rect = container.getBoundingClientRect();
    let newWidth = e.clientX - rect.left;
    newWidth = Math.max(0, Math.min(rect.width, newWidth));
    const percent = (newWidth / rect.width) * 100;

    bar.style.width = percent + '%';
    text.textContent = Math.round(percent / 100 * {{ $totalUser }}) + ' user';
}
</script>
@endsection