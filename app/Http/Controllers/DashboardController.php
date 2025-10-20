<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        /** @var User $user */
        $user->load([
            'suratDikirim',
            'notifikasis',
            'unit',
        ]);

        $recentSurat = $user->suratDikirim()
                           ->with('tujuanUnit')
                           ->latest()
                           ->take(5)
                           ->get();

        return view('dashboard', compact('user', 'recentSurat'));
    }
}