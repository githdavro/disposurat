@extends('layouts.app')

@section('title', 'Form Disposisi')
@section('header', 'Form Disposisi & Persetujuan')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-800">Form Disposisi Surat</h2>
        <p class="text-sm text-gray-600 mt-1">Berikan persetujuan dan disposisi untuk surat ini</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Surat -->
        <div class="lg:col-span-2">
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Surat</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Agenda</label>
                        <p class="mt-1 text-sm font-mono text-gray-900">{{ $surat->nomor_agenda }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Perihal</label>
                        <p class="mt-1 text-sm text-gray-900 font-medium">{{ $surat->perihal }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Asal Unit</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $surat->asalUnit->nama_unit ?? 'Eksternal' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nilai</label>
                        <p class="mt-1 text-sm font-mono text-red-600 font-semibold">
                            Rp {{ number_format($surat->nilai, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pengirim</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $surat->pengirim->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $surat->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Isi Surat</label>
                    <div class="mt-1 p-4 bg-white border border-gray-200 rounded-md">
                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $surat->isi }}</p>
                    </div>
                </div>

                @if($surat->file_path)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">File Lampiran</label>
                    <div class="mt-1">
                        <a href="{{ Storage::url($surat->file_path) }}" target="_blank" 
                           class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 text-sm">
                            <i class="fas fa-download mr-2"></i>
                            Download File
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Form Disposisi -->
        <div class="lg:col-span-1">
            <form action="{{ route('direktur.proses-disposisi', $surat->id) }}" method="POST">
                @csrf
                
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Keputusan & Disposisi</h3>

                    <!-- Status Persetujuan -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Persetujuan *</label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="radio" id="setuju" name="status_surat" value="disetujui" 
                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" checked>
                                <label for="setuju" class="ml-2 block text-sm font-medium text-gray-700">
                                    <span class="text-green-600 font-semibold">Setujui</span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="tolak" name="status_surat" value="ditolak"
                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                <label for="tolak" class="ml-2 block text-sm font-medium text-gray-700">
                                    <span class="text-red-600 font-semibold">Tolak</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Tujuan (hanya jika disetujui) -->
                    <div class="mb-4" id="unitTujuanSection">
                        <label for="tujuan_unit_id" class="block text-sm font-medium text-gray-700">Unit Tujuan *</label>
                        <select name="tujuan_unit_id" id="tujuan_unit_id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm">
                            <option value="">Pilih Unit Tujuan</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-4">
                        <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan & Instruksi *</label>
                        <textarea name="catatan" id="catatan" rows="4" required
                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm"
                                  placeholder="Berikan catatan, instruksi, atau alasan penolakan..."></textarea>
                    </div>

                    <!-- Informasi Proses -->
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700" id="infoText">
                                    Surat yang disetujui akan diarsipkan dan diteruskan ke unit tujuan
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <a href="{{ route('direktur.review') }}" 
                           class="flex-1 bg-gray-500 text-white text-center px-4 py-2 rounded-md hover:bg-gray-600 transition text-sm">
                            Batal
                        </a>
                        <button type="submit" 
                                class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition text-sm">
                            Proses Keputusan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSetuju = document.getElementById('setuju');
    const statusTolak = document.getElementById('tolak');
    const unitTujuanSection = document.getElementById('unitTujuanSection');
    const unitTujuanSelect = document.getElementById('tujuan_unit_id');
    const infoText = document.getElementById('infoText');

    function toggleUnitTujuan() {
        if (statusTolak.checked) {
            unitTujuanSection.style.display = 'none';
            unitTujuanSelect.removeAttribute('required');
            infoText.textContent = 'Surat yang ditolak akan dikembalikan ke unit pengirim dengan alasan penolakan';
        } else {
            unitTujuanSection.style.display = 'block';
            unitTujuanSelect.setAttribute('required', 'required');
            infoText.textContent = 'Surat yang disetujui akan diarsipkan dan diteruskan ke unit tujuan';
        }
    }

    statusSetuju.addEventListener('change', toggleUnitTujuan);
    statusTolak.addEventListener('change', toggleUnitTujuan);

    // Initialize
    toggleUnitTujuan();
});
</script>
@endsection