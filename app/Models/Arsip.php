<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    protected $fillable = [
        'surat_id', 'nomor_arsip', 'lokasi_arsip', 'tanggal_arsip'
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }
}