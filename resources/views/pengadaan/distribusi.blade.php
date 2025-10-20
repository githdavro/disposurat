@extends('layouts.app')

@section('title', 'Distribusi Surat')
@section('header', 'Distribusi Surat')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <form action="{{ route('pengadaan.distribusi', $surat->id) }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 gap-6">
            <div>
                <h3 class="text-lg font-semibold mb-4">Informasi Surat</h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Perihal</dt>
                        <dd class="text-sm text-gray-900">{{ $surat->perihal }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Asal Unit</dt>
                        <dd class="text-sm text-gray-900">{{ $surat->asalUnit->nama_unit ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nilai</dt>
                        <dd class="text-sm text-gray-900">
                            @if($surat->nilai)
                                Rp {{ number_format($surat->nilai, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <div>
                <label for="nomor_agenda" class="block text-sm font-medium text-gray-700">Nomor Agenda *</label>
                <input type="text" name="nomor_agenda" id="nomor_agenda" required
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
            </div>

            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
                <textarea name="catatan" id="catatan" rows="3"
                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"></textarea>
            </div>

            @if($surat->nilai >= 1000000)
            <div class="bg-blue-50 p-4 rounded-md">
                <p class="text-sm text-blue-700">
                    Surat ini memiliki nilai Rp {{ number_format($surat->nilai, 0, ',', '.') }} sehingga memerlukan persetujuan Direktur.
                </p>
            </div>
            @else
            <div class="bg-green-50 p-4 rounded-md">
                <p class="text-sm text-green-700">
                    Surat ini memiliki nilai di bawah Rp 1.000.000 sehingga tidak memerlukan persetujuan Direktur dan akan langsung diarsipkan.
                </p>
            </div>
            @endif
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('pengadaan.inbox') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                Batal
            </a>
            <button type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Proses Distribusi
            </button>
        </div>
    </form>
</div>
@endsection