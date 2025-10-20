<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\Unit;
use App\Models\Notifikasi;
use App\Models\Arsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->hasRole('unit')) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        })->except([]); // Sesuaikan jika ada method yang tidak perlu auth
    }

    public function create()
    {
        $units = Unit::where('id', '!=', Auth::user()->unit_id)->get();
        return view('unit.create_surat', compact('units'));
    }

    public function store(Request $request)
{
    // Manual role check
    if (!Auth::user()->hasRole('unit')) {
        abort(403, 'Unauthorized access.');
    }

    $request->validate([
        'perihal' => 'required|string|max:255',
        'isi' => 'required|string',
        'tujuan_unit_id' => 'required|exists:units,id',
        'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        'nilai' => 'nullable|numeric|min:0',
    ]);

    $filePath = null;
    if ($request->hasFile('file')) {
        $filePath = $request->file('file')->store('surat_files', 'public');
    }

    $surat = Surat::create([
        'perihal' => $request->perihal,
        'isi' => $request->isi,
        'asal' => 'internal',
        'pengirim_id' => Auth::id(),
        'tujuan_unit_id' => $request->tujuan_unit_id,
        'asal_unit_id' => Auth::user()->unit_id, // Pastikan ini diisi
        'file_path' => $filePath,
        'nilai' => $request->nilai,
        'status' => 'dikirim',
        'tipe_surat' => 'keluar',
    ]);

    // Notifikasi ke unit tujuan
    $this->buatNotifikasi(
        $request->tujuan_unit_id,
        'Surat Baru',
        'Surat baru dengan perihal: ' . $request->perihal,
        route('pengadaan.inbox')
    );

    return redirect()->route('unit.sent')->with('success', 'Surat berhasil dikirim.');
}

    public function sent()
    {
        $surat = Surat::where('pengirim_id', Auth::id())
                     ->with('tujuanUnit')
                     ->orderBy('created_at', 'desc')
                     ->get();
        
        return view('unit.sent', compact('surat'));
    }

        public function inbox()
        {
            $unitId = Auth::user()->unit_id;
            $userId = Auth::id();
            
            \Log::info('Inbox Unit Access:', [
                'user_id' => $userId,
                'unit_id' => $unitId,
                'user_role' => Auth::user()->role
            ]);

            $surat = Surat::where(function($query) use ($unitId) {
                    $query->where('tujuan_unit_id', $unitId)
                        ->orWhereHas('disposisis', function($q) use ($unitId) { // Pastikan disposisis (plural)
                            $q->where('tujuan_unit_id', $unitId);
                        });
                })
                ->with(['pengirim', 'tujuanUnit', 'asalUnit', 'disposisis']) // disposisis (plural)
                ->orderBy('created_at', 'desc')
                ->get();

            // Log detailed information
            foreach ($surat as $s) {
                \Log::info('Surat Info:', [
                    'id' => $s->id,
                    'perihal' => $s->perihal,
                    'tujuan_unit_id' => $s->tujuan_unit_id,
                    'asal_unit_id' => $s->asal_unit_id,
                    'status' => $s->status,
                    'disposisi_count' => $s->disposisis->count(),
                    'disposisi_units' => $s->disposisis->pluck('tujuan_unit_id')
                ]);
            }

            return view('unit.inbox', compact('surat'));
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

    public function show($id)
    {
        $surat = Surat::with([
            'pengirim', 
            'tujuanUnit', 
            'asalUnit', 
            'disposisis.dariUnit', // Perbaiki: disposisis (plural)
            'disposisis.tujuanUnit', // Perbaiki: disposisis (plural)
            'arsip'
        ])->findOrFail($id);

        // Authorization check menggunakan method dari model
        if (!$surat->userCanAccess(Auth::id(), Auth::user()->unit_id)) {
            abort(403, 'Unauthorized access.');
        }

        return view('unit.detail_surat', compact('surat'));
    }
}