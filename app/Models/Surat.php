<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $fillable = [
        'nomor_surat', 'nomor_agenda', 'perihal', 'isi', 'asal', 
        'pengirim_id', 'tujuan_unit_id', 'file_path', 'nilai', 
        'status', 'tipe_surat', 'catatan', 'asal_unit_id' // Pastikan asal_unit_id ada
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
    ];

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    public function tujuanUnit()
    {
        return $this->belongsTo(Unit::class, 'tujuan_unit_id');
    }

    public function asalUnit()
    {
        return $this->belongsTo(Unit::class, 'asal_unit_id');
    }

    public function disposisis()
    {
        return $this->hasMany(Disposisi::class);
    }

    public function arsip()
    {
        return $this->hasOne(Arsip::class);
    }

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class);
    }

    // Scope untuk surat yang perlu persetujuan direktur
    public function scopePerluPersetujuanDirektur($query)
    {
        return $query->where('nilai', '>=', 1000000)
                    ->where('status', 'diterima_pengadaan');
    }

    /**
     * Generate nomor agenda otomatis
     */
    // Di app\Models\Surat.php
    public static function generateNomorAgenda()
    {
        $year = date('Y');
        $month = date('m');
        
        $lastSurat = static::whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->orderBy('created_at', 'desc')
                        ->first();
        
        $sequence = 1;
        if ($lastSurat && $lastSurat->nomor_agenda) {
            $lastSequence = intval(substr($lastSurat->nomor_agenda, -4));
            $sequence = $lastSequence + 1;
        }
        
        return 'AGD/' . $year . '/' . $month . '/' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

        /**
     * Cek apakah user memiliki akses untuk melihat surat
     */
    /**
 * Cek apakah user memiliki akses untuk melihat surat
 */
    public function userCanAccess($userId, $unitId)
    {
        // User bisa mengakses jika:
        // 1. User adalah pengirim surat
        // 2. User adalah bagian dari unit tujuan
        // 3. User adalah bagian dari unit asal
        // 4. User adalah tujuan disposisi
        return $this->pengirim_id == $userId ||
            $this->tujuan_unit_id == $unitId ||
            $this->asal_unit_id == $unitId ||
            $this->disposisis->where('tujuan_unit_id', $unitId)->count() > 0; // disposisis (plural)
    }
}