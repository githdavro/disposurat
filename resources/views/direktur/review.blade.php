@extends('layouts.app')

@section('title', 'Review Surat')
@section('header', 'Review Surat - Persetujuan Direktur')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-800">Surat Perlu Persetujuan</h2>
        <p class="text-sm text-gray-600 mt-1">Surat dengan nilai ≥ Rp 1.000.000 memerlukan persetujuan Anda</p>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Agenda</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perihal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asal Unit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($surat as $index => $s)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="font-mono bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                            {{ $s->nomor_agenda }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="font-medium text-gray-900">{{ $s->perihal }}</div>
                        <div class="text-gray-500 text-xs mt-1">{{ Str::limit($s->isi, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $s->asalUnit->nama_unit ?? 'Eksternal' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $s->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="font-mono text-red-600 font-semibold">
                            Rp {{ number_format($s->nilai, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Menunggu Persetujuan
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('direktur.disposisi-form', $s->id) }}" 
                           class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-clipboard-check"></i> Review
                        </a>
                        <a href="{{ route('pengadaan.detail-surat', $s->id) }}" 
                           class="text-green-600 hover:text-green-900">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                        <i class="fas fa-clipboard-check text-4xl text-gray-300 mb-2"></i>
                        <p>Tidak ada surat yang perlu direview</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Proses Review</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>• Setujui atau tolak surat berdasarkan pertimbangan yang tepat</p>
                    <p>• Berikan catatan untuk setiap keputusan yang diambil</p>
                    <p>• Surat yang disetujui akan diarsipkan dan diteruskan ke unit terkait</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection