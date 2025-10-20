<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\Disposisi;
use App\Models\Arsip;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirekturController extends Controller
{
    public function review()
    {
        $surat = Surat::perluPersetujuanDirektur()
                     ->with(['pengirim', 'tujuanUnit', 'asalUnit'])
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('direktur.review', compact('surat'));
    }

    public function disposisiForm($id)
    {
        $surat = Surat::with(['pengirim', 'tujuanUnit', 'asalUnit'])->findOrFail($id);
        $units = \App\Models\Unit::whereNotIn('id', [Auth::user()->unit_id, $surat->asal_unit_id])->get();
        
        return view('direktur.disposisi_form', compact('surat', 'units'));
    }

    public function prosesDisposisi(Request $request, $id)
    {
        $request->validate([
            'tujuan_unit_id' => 'required|exists:units,id',
            'catatan' => 'required|string',
            'status_surat' => 'required|in:disetujui,ditolak',
        ]);

        $surat = Surat::findOrFail($id);
        
        // Update status surat
        $surat->update([
            'status' => $request->status_surat,
            'catatan' => $request->catatan
        ]);

        if ($request->status_surat == 'disetujui') {
            // Buat disposisi ke unit tujuan
            Disposisi::create([
                'surat_id' => $surat->id,
                'dari_unit_id' => Auth::user()->unit_id,
                'tujuan_unit_id' => $request->tujuan_unit_id,
                'catatan' => $request->catatan,
                'status' => 'dikirim',
            ]);

            // Buat arsip
            Arsip::create([
                'surat_id' => $surat->id,
                'nomor_arsip' => 'ARS/' . date('Y/m/d/') . $surat->id,
                'lokasi_arsip' => 'Rak B - ' . $surat->id,
            ]);

            // Notifikasi ke unit tujuan
            $this->buatNotifikasi(
                $request->tujuan_unit_id,
                'Disposisi Surat',
                'Surat dengan perihal: ' . $surat->perihal . ' telah didisposisikan ke unit Anda',
                route('unit.inbox')
            );
        }

        // Notifikasi ke unit pengirim
        $this->buatNotifikasi(
            $surat->asal_unit_id,
            'Status Surat',
            'Surat dengan perihal: ' . $surat->perihal . ' ' . $request->status_surat,
            route('unit.sent')
        );

        return redirect()->route('direktur.review')->with('success', 'Surat berhasil diproses.');
    }

    public function arsip()
    {
        $arsip = Arsip::with('surat.pengirim', 'surat.asalUnit')
                     ->orderBy('tanggal_arsip', 'desc')
                     ->get();

        return view('direktur.arsip', compact('arsip'));
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