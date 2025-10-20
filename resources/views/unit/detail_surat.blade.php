@extends('layouts.app')

@section('title', 'Detail Surat')
@section('header', 'Detail Surat')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <!-- Tombol Kembali -->
    <div class="mb-4">
        <a href="{{ url()->previous() }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Surat</h3>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Perihal</dt>
                    <dd class="text-sm text-gray-900">{{ $surat->perihal }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Isi Surat</dt>
                    <dd class="text-sm text-gray-900 mt-1 bg-gray-50 p-3 rounded">{{ $surat->isi }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Pengirim</dt>
                    <dd class="text-sm text-gray-900">{{ $surat->pengirim->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Unit Asal</dt>
                    <dd class="text-sm text-gray-900">{{ $surat->asalUnit->nama_unit ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Unit Tujuan</dt>
                    <dd class="text-sm text-gray-900">{{ $surat->tujuanUnit->nama_unit }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                    <dd class="text-sm text-gray-900">{{ $surat->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="text-sm">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($surat->status == 'disetujui') bg-green-100 text-green-800
                            @elseif($surat->status == 'ditolak') bg-red-100 text-red-800
                            @elseif($surat->status == 'dikirim') bg-blue-100 text-blue-800
                            @elseif($surat->status == 'diterima_pengadaan') bg-yellow-100 text-yellow-800
                            @elseif($surat->status == 'diarsipkan') bg-gray-100 text-gray-800
                            @elseif($surat->status == 'diproses') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $surat->status)) }}
                        </span>
                    </dd>
                </div>
                
                <!-- Tampilkan Nomor Agenda untuk semua role jika ada -->
                @if($surat->nomor_agenda)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nomor Agenda</dt>
                    <dd class="text-sm text-gray-900 font-semibold">{{ $surat->nomor_agenda }}</dd>
                </div>
                @endif

                @if($surat->nilai)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nilai</dt>
                    <dd class="text-sm text-gray-900">Rp {{ number_format($surat->nilai, 0, ',', '.') }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <div>
            <!-- File Lampiran -->
            @if($surat->file_path)
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">File Lampiran</h3>
                <a href="{{ asset('storage/' . $surat->file_path) }}" 
                   target="_blank" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    <i class="fas fa-download mr-2"></i>
                    Download File
                </a>
            </div>
            @endif

            <!-- Informasi Proses untuk semua role -->
            @if($surat->status != 'dikirim')
            <div class="bg-gray-50 p-4 rounded-lg border">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Informasi Proses</h3>
                
                @if($surat->nomor_agenda)
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-700">Nomor Agenda:</span>
                    <span class="text-sm text-gray-900 ml-2 font-semibold">{{ $surat->nomor_agenda }}</span>
                </div>
                @endif

                @if($surat->arsip)
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-700">Nomor Arsip:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $surat->arsip->nomor_arsip }}</span>
                </div>
                @endif

                <!-- Tampilkan catatan dari disposisi terakhir -->
                @php
                    $lastDisposisi = $surat->disposisis->sortByDesc('created_at')->first();
                @endphp

                @if($lastDisposisi && $lastDisposisi->catatan)
                <div>
                    <span class="text-sm font-medium text-gray-700">Catatan:</span>
                    <p class="text-sm text-gray-900 mt-1 bg-white p-2 rounded border">{{ $lastDisposisi->catatan }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Tombol Aksi untuk Disposisi -->
    @if($surat->disposisis->where('tujuan_unit_id', Auth::user()->unit_id)->where('status', 'dikirim')->count() > 0)
    <div class="mt-6 border-t pt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Disposisi</h3>
        @php
            $disposisiUnit = $surat->disposisis->where('tujuan_unit_id', Auth::user()->unit_id)->where('status', 'dikirim')->first();
        @endphp
        @if($disposisiUnit)
        <div class="flex space-x-3">
            <form action="{{ route('disposisi.terima', $disposisiUnit->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                    <i class="fas fa-check mr-2"></i>Terima Disposisi
                </button>
            </form>
        </div>
        @endif
    </div>
    @endif

    <!-- Riwayat Disposisi -->
    @if($surat->disposisis->count() > 0)
    <div class="mt-8 border-t pt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Disposisi</h3>
        <div class="space-y-3">
            @foreach($surat->disposisis->sortBy('created_at') as $disposisi)
            <div class="border border-gray-200 rounded-lg p-4 {{ $disposisi->tujuan_unit_id == Auth::user()->unit_id ? 'bg-blue-50 border-blue-200' : '' }}">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4 mb-2">
                            <p class="text-sm font-medium text-gray-900">
                                <i class="fas fa-arrow-right text-blue-500 mr-1"></i>
                                Dari: {{ $disposisi->dariUnit->nama_unit ?? 'Tidak diketahui' }}
                            </p>
                            <p class="text-sm font-medium text-gray-900">
                                <i class="fas fa-arrow-left text-green-500 mr-1"></i>
                                Ke: {{ $disposisi->tujuanUnit->nama_unit ?? 'Tidak diketahui' }}
                            </p>
                        </div>
                        @if($disposisi->catatan)
                        <div class="mt-2">
                            <p class="text-sm font-medium text-gray-700">Catatan:</p>
                            <p class="text-sm text-gray-600 mt-1 bg-white p-2 rounded border">{{ $disposisi->catatan }}</p>
                        </div>
                        @endif
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="far fa-clock mr-1"></i>
                            {{ $disposisi->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div class="text-right ml-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($disposisi->status == 'dikirim') bg-blue-100 text-blue-800
                            @elseif($disposisi->status == 'diterima') bg-green-100 text-green-800
                            @elseif($disposisi->status == 'selesai') bg-gray-100 text-gray-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($disposisi->status) }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection