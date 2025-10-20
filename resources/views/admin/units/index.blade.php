@extends('layouts.app')

@section('title', 'Manajemen Unit')
@section('header', 'Manajemen Unit')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Unit</h2>
        <button onclick="openCreateModal()" 
                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
            Tambah Unit
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nama Unit
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Kode Unit
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Jumlah User
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($units as $unit)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $unit->nama_unit }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $unit->kode_unit }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $unit->users_count }} user</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openEditModal({{ $unit }})" 
                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                        <form action="{{ route('admin.units.destroy', $unit->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus unit ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                        Tidak ada unit
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $units->links() }}
    </div>
</div>

<!-- Create Unit Modal -->
<div id="createUnitModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Unit Baru</h3>
            
            <form action="{{ route('admin.units.create') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="nama_unit" class="block text-sm font-medium text-gray-700">Nama Unit</label>
                    <input type="text" name="nama_unit" id="nama_unit" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                </div>
                
                <div class="mb-4">
                    <label for="kode_unit" class="block text-sm font-medium text-gray-700">Kode Unit</label>
                    <input type="text" name="kode_unit" id="kode_unit" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                           placeholder="Contoh: U001, PENGADAAN">
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCreateUnitModal()"
                            class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('createUnitModal').classList.remove('hidden');
    }

    function closeCreateUnitModal() {
        document.getElementById('createUnitModal').classList.add('hidden');
    }

    function openEditModal(unit) {
        // Implement edit modal similar to create
        alert('Edit functionality for: ' + unit.nama_unit);
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('createUnitModal');
        if (event.target === modal) {
            closeCreateUnitModal();
        }
    }
</script>
@endsection