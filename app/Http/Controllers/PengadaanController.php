<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\Unit;
use App\Models\Notifikasi;
use App\Models\Disposisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengadaanController extends Controller
{
    public function inbox()
    {
        $surat = Surat::where('tujuan_unit_id', Auth::user()->unit_id)
                     ->orWhereHas('disposisis', function($query) {
                         $query->where('tujuan_unit_id', Auth::user()->unit_id);
                     })
                     ->with(['pengirim', 'tujuanUnit', 'asalUnit'])
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('pengadaan.inbox', compact('surat'));
    }

    public function detailSurat($id)
{
    $surat = Surat::with(['pengirim', 'tujuanUnit', 'asalUnit', 'disposisis.dariUnit', 'disposisis.tujuanUnit'])
                 ->findOrFail($id);
    
    $units = Unit::whereNotIn('kode_unit', ['PENGADAAN'])->get();

    return view('pengadaan.detail_surat', compact('surat', 'units'));
}

    public function distribusi(Request $request, $id)
    {
        $request->validate([
            'tujuan_unit_id' => 'required|exists:units,id',
            'catatan' => 'nullable|string',
        ]);

        $surat = Surat::findOrFail($id);
        
        // Generate nomor agenda otomatis
        $nomorAgenda = Surat::generateNomorAgenda();
        
        // Update nomor agenda dan status
        $surat->update([
            'nomor_agenda' => $nomorAgenda,
            'status' => 'diterima_pengadaan'
        ]);

        // Buat disposisi ke direktur jika nilai >= 1 juta
        if ($surat->nilai >= 1000000) {
            $direkturUnit = Unit::where('kode_unit', 'DIREKTUR')->first();
            
            Disposisi::create([
                'surat_id' => $surat->id,
                'dari_unit_id' => Auth::user()->unit_id,
                'tujuan_unit_id' => $direkturUnit->id,
                'catatan' => $request->catatan,
                'status' => 'dikirim',
            ]);

            // Notifikasi ke direktur
            $this->buatNotifikasi(
                $direkturUnit->id,
                'Surat Perlu Persetujuan',
                'Surat dengan nomor agenda ' . $nomorAgenda . ' perlu persetujuan. Perihal: ' . $surat->perihal,
                route('direktur.review')
            );

            return redirect()->route('pengadaan.inbox')->with('success', 'Surat berhasil didistribusikan ke Direktur dengan nomor agenda: ' . $nomorAgenda);

        } else {
            // Langsung arsipkan jika nilai di bawah 1 juta
            $surat->update(['status' => 'diarsipkan']);
            
            // Buat arsip
            \App\Models\Arsip::create([
                'surat_id' => $surat->id,
                'nomor_arsip' => 'ARS/' . date('Y/m/d/') . $surat->id,
                'lokasi_arsip' => 'Rak A - ' . $surat->id,
            ]);

            // Notifikasi ke unit pengirim
            $this->buatNotifikasi(
                $surat->asal_unit_id,
                'Surat Selesai Diproses',
                'Surat dengan nomor agenda ' . $nomorAgenda . ' telah selesai diproses. Perihal: ' . $surat->perihal,
                route('unit.sent')
            );

            return redirect()->route('pengadaan.inbox')->with('success', 'Surat berhasil diproses dan diarsipkan dengan nomor agenda: ' . $nomorAgenda);
        }
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