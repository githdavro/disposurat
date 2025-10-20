@extends('layouts.app')

@section('title', 'Buat Surat Baru')
@section('header', 'Buat Surat Baru')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <form action="{{ route('surat.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="perihal" class="block text-sm font-medium text-gray-700">Perihal *</label>
                <input type="text" name="perihal" id="perihal" required
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
            </div>

            <div>
                <label for="tujuan_unit_id" class="block text-sm font-medium text-gray-700">Tujuan Unit *</label>
                <select name="tujuan_unit_id" id="tujuan_unit_id" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    <option value="">Pilih Unit Tujuan</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label for="isi" class="block text-sm font-medium text-gray-700">Isi Surat *</label>
                <textarea name="isi" id="isi" rows="6" required
                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"></textarea>
            </div>

            <div>
                <label for="nilai" class="block text-sm font-medium text-gray-700">Nilai (Rp)</label>
                <input type="number" name="nilai" id="nilai" step="0.01"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                       placeholder="Kosongkan jika tidak ada nilai">
            </div>

            <div>
                <label for="file" class="block text-sm font-medium text-gray-700">File Surat</label>
                <input type="file" name="file" id="file"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                       accept=".pdf,.doc,.docx">
                <p class="text-sm text-gray-500 mt-1">Format: PDF, DOC, DOCX (Maks: 2MB)</p>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('unit.sent') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                Batal
            </a>
            <button type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Kirim Surat
            </button>
        </div>
    </form>
</div>
@endsection