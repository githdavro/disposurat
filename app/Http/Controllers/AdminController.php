<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\User;
use App\Models\Unit;
use App\Models\Notifikasi;
use App\Models\Disposisi;
use App\Models\Arsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Manual role check
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }

        // Statistik utama
        $totalSurat = Surat::count();
        $totalUser = User::count();
        $totalUnit = Unit::count();
        $suratBelumDiproses = Surat::where('status', 'dikirim')->count();

        // Statistik surat per bulan (untuk chart)
        $suratPerBulan = Surat::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('total', 'month')
        ->toArray();

        // Isi bulan yang kosong
        $suratChartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $suratChartData[] = $suratPerBulan[$i] ?? 0;
        }

        // Statistik per role
        $userPerRole = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('roles.name as role_name', DB::raw('COUNT(*) as total'))
            ->groupBy('roles.name')
            ->get();

        // Aktivitas terbaru
        $recentSurat = Surat::with(['pengirim', 'tujuanUnit', 'asalUnit'])
                           ->latest()
                           ->take(5)
                           ->get();

        // Surat dengan status bermasalah
        $suratTertunda = Surat::whereIn('status', ['dikirim', 'diterima_pengadaan'])
                             ->with(['pengirim', 'tujuanUnit'])
                             ->latest()
                             ->take(5)
                             ->get();

        return view('admin.dashboard', compact(
            'totalSurat', 
            'totalUser', 
            'totalUnit', 
            'suratBelumDiproses',
            'suratChartData',
            'userPerRole',
            'recentSurat',
            'suratTertunda'
        ));
    }

    public function userManagement()
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }

        $users = User::with(['unit', 'roles'])->latest()->paginate(10);
        $units = Unit::all();
        $roles = \Spatie\Permission\Models\Role::all();

        return view('admin.users.index', compact('users', 'units', 'roles'));
    }

    public function unitManagement()
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }

        $units = Unit::withCount('users')->latest()->paginate(10);

        return view('admin.units.index', compact('units'));
    }

    public function systemLogs()
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }

        // Ambil log aktivitas terbaru (contoh sederhana)
        $recentActivities = Surat::with(['pengirim'])
                               ->select('id', 'perihal', 'status', 'pengirim_id', 'created_at')
                               ->latest()
                               ->take(20)
                               ->get();

        return view('admin.logs', compact('recentActivities'));
    }

    public function updateUser(Request $request, User $user)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'unit_id' => 'nullable|exists:units,id',
            'role' => 'required|exists:roles,name'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'unit_id' => $request->unit_id,
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users')->with('success', 'User berhasil diperbarui.');
    }

    public function createUser(Request $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'unit_id' => 'nullable|exists:units,id',
            'role' => 'required|exists:roles,name'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'unit_id' => $request->unit_id,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users')->with('success', 'User berhasil dibuat.');
    }

    public function createUnit(Request $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'nama_unit' => 'required|string|max:255',
            'kode_unit' => 'required|string|max:10|unique:units',
        ]);

        Unit::create($request->all());

        return redirect()->route('admin.units')->with('success', 'Unit berhasil dibuat.');
    }
}