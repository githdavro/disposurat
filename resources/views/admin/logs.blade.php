@extends('layouts.app')

@section('title', 'System Logs')
@section('header', 'System Logs')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Aktivitas Sistem Terbaru</h2>
        <p class="text-sm text-gray-600 mt-1">Log aktivitas 20 transaksi terakhir</p>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aktivitas
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        User
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Waktu
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentActivities as $activity)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ Str::limit($activity->perihal, 60) }}
                        </div>
                        <div class="text-xs text-gray-500">ID: {{ $activity->id }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $activity->pengirim->name ?? 'System' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($activity->status == 'disetujui') bg-green-100 text-green-800
                            @elseif($activity->status == 'ditolak') bg-red-100 text-red-800
                            @elseif($activity->status == 'dikirim') bg-blue-100 text-blue-800
                            @elseif($activity->status == 'diterima_pengadaan') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $activity->created_at->format('d/m/Y H:i') }}</div>
                        <div class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                        Tidak ada aktivitas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Informasi Sistem</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p><strong>Waktu Server:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
                <p><strong>Timezone:</strong> {{ config('app.timezone') }}</p>
                <p><strong>Environment:</strong> {{ app()->environment() }}</p>
            </div>
            <div>
                <p><strong>Versi Laravel:</strong> {{ app()->version() }}</p>
                <p><strong>Versi PHP:</strong> {{ PHP_VERSION }}</p>
                <p><strong>Database:</strong> {{ config('database.default') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection