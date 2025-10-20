<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::where('user_id', Auth::id())
                               ->orderBy('created_at', 'desc')
                               ->paginate(10); // Gunakan paginate instead of get

        return view('unit.notifikasi', compact('notifikasis'));
    }

    public function markAsRead($id)
    {
        $notifikasi = Notifikasi::where('user_id', Auth::id())
                              ->where('id', $id)
                              ->firstOrFail();

        $notifikasi->update(['dibaca' => true]);

        return redirect($notifikasi->link);
    }

    public function markAllAsRead()
    {
        Notifikasi::where('user_id', Auth::id())
                 ->where('dibaca', false)
                 ->update(['dibaca' => true]);

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}