@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

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
                <p class="text-2xl font-semibold text-gray-900">{{ $user->suratDikirim->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-paper-plane text-green-500 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Surat Terkirim</p>
                <p class="text-2xl font-semibold text-gray-900">
                    {{ $user->suratDikirim->where('status', 'dikirim')->count() }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-yellow-500 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Disetujui</p>
                <p class="text-2xl font-semibold text-gray-900">
                    {{ $user->suratDikirim->where('status', 'disetujui')->count() }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-bell text-red-500 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Notifikasi</p>
                <p class="text-2xl font-semibold text-gray-900">
                    {{ $user->notifikasis->where('dibaca', false)->count() }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
        <div class="space-y-3">
            @if(Auth::user()->hasRole('unit'))
            <a href="{{ route('unit.create-surat') }}" 
               class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                <i class="fas fa-plus text-blue-500 mr-3"></i>
                <span class="text-sm font-medium text-gray-700">Buat Surat Baru</span>
            </a>
            <a href="{{ route('unit.sent') }}" 
               class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                <i class="fas fa-paper-plane text-green-500 mr-3"></i>
                <span class="text-sm font-medium text-gray-700">Lihat Surat Terkirim</span>
            </a>
            @endif

            @if(Auth::user()->hasRole('pengadaan'))
            <a href="{{ route('pengadaan.inbox') }}" 
               class="flex items-center p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                <i class="fas fa-inbox text-yellow-500 mr-3"></i>
                <span class="text-sm font-medium text-gray-700">Inbox Pengadaan</span>
            </a>
            @endif

            @if(Auth::user()->hasRole('direktur'))
            <a href="{{ route('direktur.review') }}" 
               class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                <i class="fas fa-clipboard-check text-purple-500 mr-3"></i>
                <span class="text-sm font-medium text-gray-700">Review Surat</span>
            </a>
            <a href="{{ route('direktur.arsip') }}" 
               class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <i class="fas fa-archive text-gray-500 mr-3"></i>
                <span class="text-sm font-medium text-gray-700">Arsip Surat</span>
            </a>
            @endif

            <a href="{{ route('notifikasi.index') }}" 
               class="flex items-center p-3 bg-red-50 rounded-lg hover:bg-red-100 transition">
                <i class="fas fa-bell text-red-500 mr-3"></i>
                <span class="text-sm font-medium text-gray-700">Lihat Notifikasi</span>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h3>
        <div class="space-y-4">
            @forelse($recentSurat as $surat)
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="fas fa-envelope 
                        @if($surat->status == 'disetujui') text-green-500
                        @elseif($surat->status == 'ditolak') text-red-500
                        @elseif($surat->status == 'dikirim') text-blue-500
                        @else text-gray-500 @endif">
                    </i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $surat->perihal }}</p>
                    <p class="text-xs text-gray-500">
                        Ke: {{ $surat->tujuanUnit->nama_unit }} • 
                        {{ $surat->created_at->diffForHumans() }}
                    </p>
                    <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full 
                        @if($surat->status == 'disetujui') bg-green-100 text-green-800
                        @elseif($surat->status == 'ditolak') bg-red-100 text-red-800
                        @elseif($surat->status == 'dikirim') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst(str_replace('_', ' ', $surat->status)) }}
                    </span>
                </div>
            </div>
            @empty
            <div class="text-center py-4">
                <i class="fas fa-inbox text-2xl text-gray-300 mb-2"></i>
                <p class="text-sm text-gray-500">Belum ada aktivitas</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Informasi Sistem -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-400 text-xl"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Informasi Sistem</h3>
            <div class="mt-2 text-sm text-blue-700">
                <p class="mb-1"><strong>Role:</strong> {{ $user->getRoleNames()->first() }}</p>
                <p class="mb-1"><strong>Unit:</strong> {{ $user->unit->nama_unit ?? '-' }}</p>
                <p class="mb-1"><strong>Login Terakhir:</strong> {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Pertama kali' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection