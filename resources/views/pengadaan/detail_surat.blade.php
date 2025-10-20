@extends('layouts.app')

@section('title', 'Detail Surat')
@section('header', 'Detail Surat')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Surat</h3>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Perihal</dt>
                    <dd class="text-sm text-gray-900">{{ $surat->perihal }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Pengirim</dt>
                    <dd class="text-sm text-gray-900">
                        {{ $surat->pengirim->name ?? 'Tidak diketahui' }} - 
                        {{ $surat->asalUnit->nama_unit ?? 'Unit tidak diketahui' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                    <dd class="text-sm text-gray-900">{{ $surat->created_at->translatedFormat('d F Y H:i') }}</dd>
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
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $surat->status)) }}
                        </span>
                    </dd>
                </div>
                @if($surat->nomor_agenda && (Auth::user()->hasRole('pengadaan') || Auth::user()->hasRole('admin')))
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
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Isi Surat</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $surat->isi }}</p>
            </div>

            @if($surat->file_path)
            <div class="mt-4">
                <dt class="text-sm font-medium text-gray-500">File Lampiran</dt>
                <dd class="text-sm text-gray-900">
                    <a href="{{ asset('storage/' . $surat->file_path) }}" 
                       target="_blank" 
                       class="text-blue-600 hover:text-blue-800 flex items-center">
                        <i class="fas fa-file-download mr-2"></i>
                        Download File
                    </a>
                </dd>
            </div>
            @endif
        </div>
    </div>

    <!-- Form Distribusi untuk Pengadaan -->
    @if(Auth::user()->hasRole('pengadaan') && $surat->status == 'dikirim')
    <div class="mt-8 border-t pt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Proses Surat</h3>
        
        <form action="{{ route('pengadaan.distribusi', $surat->id) }}" method="POST">
            @csrf
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-yellow-500 mr-2"></i>
                    <p class="text-sm text-yellow-700">
                        <strong>Nomor agenda akan digenerate otomatis:</strong> AGD/YYYY/MM/XXXX
                    </p>
                </div>
            </div>

            @if($surat->nilai >= 1000000)
            <div class="bg-blue-50 p-4 rounded-md mb-4">
                <p class="text-sm text-blue-700">
                    <strong>Perhatian:</strong> Surat ini memiliki nilai Rp {{ number_format($surat->nilai, 0, ',', '.') }} 
                    sehingga memerlukan persetujuan Direktur.
                </p>
            </div>
            @else
            <div class="bg-green-50 p-4 rounded-md mb-4">
                <p class="text-sm text-green-700">
                    <strong>Informasi:</strong> Surat ini memiliki nilai di bawah Rp 1.000.000 
                    sehingga akan langsung diarsipkan.
                </p>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="tujuan_unit_id" class="block text-sm font-medium text-gray-700">Tujuan Distribusi *</label>
                    <select name="tujuan_unit_id" id="tujuan_unit_id" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="">Pilih Tujuan</option>
                        @foreach($units as $unit)
                            @if($unit->kode_unit != 'PENGADAAN')
                                <option value="{{ $unit->id }}" 
                                    @if($surat->nilai >= 1000000 && $unit->kode_unit == 'DIREKTUR') selected @endif>
                                    {{ $unit->nama_unit }}
                                    @if($surat->nilai >= 1000000 && $unit->kode_unit == 'DIREKTUR')
                                        (Wajib untuk nilai ≥ Rp 1.000.000)
                                    @endif
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan Distribusi</label>
                <textarea name="catatan" id="catatan" rows="3"
                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                          placeholder="Tambahkan catatan untuk proses distribusi...">{{ old('catatan') }}</textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('pengadaan.inbox') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                    Kembali
                </a>
                <button type="submit" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                    Proses Surat
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- Catatan Distribusi hanya untuk role tertentu -->
    @if($surat->status != 'dikirim' && (Auth::user()->hasRole('pengadaan') || Auth::user()->hasRole('admin')))
    <div class="mt-8 border-t pt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Proses</h3>
        <div class="bg-gray-50 p-4 rounded-lg">
            @if($surat->nomor_agenda)
            <p class="text-sm text-gray-700 mb-2">
                <strong>Nomor Agenda:</strong> {{ $surat->nomor_agenda }}
            </p>
            @endif
            @if($surat->arsip)
            <p class="text-sm text-gray-700">
                <strong>Nomor Arsip:</strong> {{ $surat->arsip->nomor_arsip }}
            </p>
            @endif
        </div>
    </div>
    @endif

    <!-- Riwayat Disposisi -->
    @if($surat->disposisis->count() > 0)
    <div class="mt-8 border-t pt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Disposisi</h3>
        <div class="space-y-3">
            @foreach($surat->disposisis as $disposisi)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-900">
                            Dari: {{ $disposisi->dariUnit->nama_unit ?? 'Tidak diketahui' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            Ke: {{ $disposisi->tujuanUnit->nama_unit ?? 'Tidak diketahui' }}
                        </p>
                        @if($disposisi->catatan)
                        <p class="text-sm text-gray-600 mt-1">
                            Catatan: {{ $disposisi->catatan }}
                        </p>
                        @endif
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($disposisi->status == 'dikirim') bg-blue-100 text-blue-800
                            @elseif($disposisi->status == 'diterima') bg-green-100 text-green-800
                            @elseif($disposisi->status == 'selesai') bg-gray-100 text-gray-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($disposisi->status) }}
                        </span>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $disposisi->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@if(Auth::user()->hasRole('pengadaan') && $surat->status == 'dikirim')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const suratNilai = {{ $surat->nilai ?? 0 }};
        const tujuanSelect = document.getElementById('tujuan_unit_id');
        
        // Auto-select Direktur jika nilai >= 1 juta
        if (suratNilai >= 1000000) {
            const options = tujuanSelect.options;
            for (let i = 0; i < options.length; i++) {
                if (options[i].text.includes('DIREKTUR')) {
                    tujuanSelect.value = options[i].value;
                    // Non-aktifkan pilihan lain
                    for (let j = 0; j < options.length; j++) {
                        if (options[j].value !== options[i].value) {
                            options[j].disabled = true;
                        }
                    }
                    break;
                }
            }
        }
    });
</script>
@endif
@endsection