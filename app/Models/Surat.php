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
    public static function generateNomorAgenda()
    {
        $tahun = date('Y');
        $bulan = date('m');
        
        // Hitung jumlah surat di bulan ini
        $count = Surat::whereYear('created_at', $tahun)
                     ->whereMonth('created_at', $bulan)
                     ->count();
        
        $nextNumber = $count + 1;
        
        return "AGD/{$tahun}/{$bulan}/" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}