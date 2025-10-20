@extends('layouts.app')

@section('title', 'Arsip Surat')
@section('header', 'Arsip Surat')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-800">Arsip Surat Disetujui</h2>
        <p class="text-sm text-gray-600 mt-1">Daftar surat yang telah disetujui dan diarsipkan</p>
    </div>

    <!-- Filter dan Pencarian -->
    <div class="mb-6 bg-gray-50 rounded-lg p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Pencarian</label>
                <input type="text" id="search" placeholder="Cari berdasarkan perihal atau nomor arsip..."
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm">
            </div>
            <div>
                <label for="unit_filter" class="block text-sm font-medium text-gray-700">Filter Unit</label>
                <select id="unit_filter" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm">
                    <option value="">Semua Unit</option>
                    <option value="1">Unit 1</option>
                    <option value="2">Unit 2</option>
                    <option value="3">Bagian Pengadaan</option>
                </select>
            </div>
            <div>
                <label for="date_filter" class="block text-sm font-medium text-gray-700">Filter Tanggal</label>
                <input type="month" id="date_filter" 
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Arsip</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Surat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perihal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asal Unit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Arsip</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi Arsip</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($arsip as $index => $a)
                <tr class="hover:bg-gray-50" data-unit="{{ $a->surat->asal_unit_id }}" 
                    data-date="{{ $a->tanggal_arsip->format('Y-m') }}"
                    data-search="{{ strtolower($a->surat->perihal . ' ' . $a->nomor_arsip) }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="font-mono bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                            {{ $a->nomor_arsip }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $a->surat->nomor_surat ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="font-medium text-gray-900">{{ $a->surat->perihal }}</div>
                        <div class="text-gray-500 text-xs mt-1">{{ Str::limit($a->surat->isi, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $a->surat->asalUnit->nama_unit ?? 'Eksternal' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $a->tanggal_arsip->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">
                            {{ $a->lokasi_arsip }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($a->surat->nilai)
                            <span class="font-mono">Rp {{ number_format($a->surat->nilai, 0, ',', '.') }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="showArsipDetail({{ $a }})" 
                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-eye"></i>
                        </button>
                        @if($a->surat->file_path)
                        <a href="{{ Storage::url($a->surat->file_path) }}" target="_blank" 
                           class="text-green-600 hover:text-green-900">
                            <i class="fas fa-download"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                        <i class="fas fa-archive text-4xl text-gray-300 mb-2"></i>
                        <p>Belum ada surat yang diarsipkan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Statistik -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-archive text-blue-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-800">Total Arsip</p>
                    <p class="text-2xl font-semibold text-blue-900">{{ $arsip->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-file-invoice-dollar text-green-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">Nilai Total</p>
                    <p class="text-lg font-semibold text-green-900">
                        Rp {{ number_format($arsip->sum('surat.nilai'), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-building text-purple-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-purple-800">Unit Terbanyak</p>
                    <p class="text-lg font-semibold text-purple-900">
                        {{ $arsip->groupBy('surat.asal_unit_id')->sortDesc()->keys()->first() ? 'Unit ' . $arsip->groupBy('surat.asal_unit_id')->sortDesc()->keys()->first() : '-' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-alt text-orange-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-orange-800">Bulan Ini</p>
                    <p class="text-2xl font-semibold text-orange-900">
                        {{ $arsip->where('tanggal_arsip', '>=', now()->startOfMonth())->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Arsip -->
    <div id="arsipDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Arsip Surat</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Arsip</label>
                        <p id="detail-nomor-arsip" class="mt-1 text-sm font-mono text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Surat</label>
                        <p id="detail-nomor-surat" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Perihal</label>
                        <p id="detail-perihal" class="mt-1 text-sm text-gray-900 font-medium"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Asal Unit</label>
                        <p id="detail-asal-unit" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Lokasi Arsip</label>
                        <p id="detail-lokasi" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Arsip</label>
                        <p id="detail-tanggal" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Nilai</label>
                        <p id="detail-nilai" class="mt-1 text-sm font-mono text-gray-900"></p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Isi Surat</label>
                    <p id="detail-isi" class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded"></p>
                </div>

                <div class="flex justify-end pt-4 border-t">
                    <button onclick="closeArsipDetail()" 
                            class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showArsipDetail(arsip) {
    document.getElementById('detail-nomor-arsip').textContent = arsip.nomor_arsip;
    document.getElementById('detail-nomor-surat').textContent = arsip.surat.nomor_surat || '-';
    document.getElementById('detail-perihal').textContent = arsip.surat.perihal;
    document.getElementById('detail-asal-unit').textContent = arsip.surat.asal_unit ? arsip.surat.asal_unit.nama_unit : 'Eksternal';
    document.getElementById('detail-lokasi').textContent = arsip.lokasi_arsip;
    document.getElementById('detail-tanggal').textContent = new Date(arsip.tanggal_arsip).toLocaleString('id-ID');
    document.getElementById('detail-nilai').textContent = arsip.surat.nilai ? 'Rp ' + arsip.surat.nilai.toLocaleString('id-ID') : '-';
    document.getElementById('detail-isi').textContent = arsip.surat.isi;
    
    document.getElementById('arsipDetailModal').classList.remove('hidden');
}

function closeArsipDetail() {
    document.getElementById('arsipDetailModal').classList.add('hidden');
}

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const unitFilter = document.getElementById('unit_filter');
    const dateFilter = document.getElementById('date_filter');
    const rows = document.querySelectorAll('tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const unitValue = unitFilter.value;
        const dateValue = dateFilter.value;

        rows.forEach(row => {
            const searchData = row.getAttribute('data-search');
            const unitData = row.getAttribute('data-unit');
            const dateData = row.getAttribute('data-date');

            const matchesSearch = searchData.includes(searchTerm);
            const matchesUnit = !unitValue || unitData === unitValue;
            const matchesDate = !dateValue || dateData === dateValue;

            if (matchesSearch && matchesUnit && matchesDate) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    unitFilter.addEventListener('change', filterTable);
    dateFilter.addEventListener('change', filterTable);
});
</script>
@endsection