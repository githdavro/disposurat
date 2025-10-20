<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\Surat;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisposisiController extends Controller
{
    public function terimaDisposisi($id)
    {
        $disposisi = Disposisi::where('id', $id)
                             ->where('tujuan_unit_id', Auth::user()->unit_id)
                             ->firstOrFail();

        $disposisi->update(['status' => 'diterima']);

        // Update status surat
        $disposisi->surat->update(['status' => 'diproses']);

        return redirect()->back()->with('success', 'Disposisi berhasil diterima.');
    }

    public function selesaikanDisposisi($id)
    {
        $disposisi = Disposisi::where('id', $id)
                             ->where('tujuan_unit_id', Auth::user()->unit_id)
                             ->firstOrFail();

        $disposisi->update(['status' => 'selesai']);

        // Notifikasi ke unit pengirim disposisi
        $this->buatNotifikasi(
            $disposisi->dari_unit_id,
            'Disposisi Selesai',
            'Disposisi surat ' . $disposisi->surat->perihal . ' telah diselesaikan',
            route('direktur.arsip')
        );

        return redirect()->back()->with('success', 'Disposisi berhasil diselesaikan.');
    }

    private function buatNotifikasi($unitId, $judul, $pesan, $link)
    {
        $users = \App\Models\User::where('unit_id', $unitId)->get();
        
        foreach ($users as $user) {
            Notifikasi::create([
                'user_id' => $user->id,
                'judul' => $judul,
                'pesan' => $pesan,
                'link' => $link,
            ]);
        }
    }
}