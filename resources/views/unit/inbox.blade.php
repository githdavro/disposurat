@extends('layouts.app')

@section('title', 'Surat Masuk')
@section('header', 'Surat Masuk')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

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
                        Sumber
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($surat as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $item->perihal }}</div>
                        @if($item->disposisis->count() > 0)
                        <div class="text-xs text-blue-600 mt-1">
                            <i class="fas fa-share-alt mr-1"></i>Disposisi
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $item->pengirim->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">
                            @if($item->tujuan_unit_id == Auth::user()->unit_id)
                                Langsung
                            @else
                                Disposisi
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $item->created_at->format('d/m/Y H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($item->status == 'disetujui') bg-green-100 text-green-800
                            @elseif($item->status == 'ditolak') bg-red-100 text-red-800
                            @elseif($item->status == 'dikirim') bg-blue-100 text-blue-800
                            @elseif($item->status == 'diterima_pengadaan') bg-yellow-100 text-yellow-800
                            @elseif($item->status == 'diarsipkan') bg-gray-100 text-gray-800
                            @elseif($item->status == 'diproses') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('unit.detail-surat', $item->id) }}" 
                           class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded-md">
                            <i class="fas fa-eye mr-1"></i>Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                        <i class="fas fa-inbox text-2xl text-gray-300 mb-2 block"></i>
                        Tidak ada surat masuk
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection