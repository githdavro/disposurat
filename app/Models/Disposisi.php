<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    protected $fillable = [
        'surat_id', 'dari_unit_id', 'tujuan_unit_id', 'catatan', 
        'status', 'tanggal_disposisi'
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    public function dariUnit()
    {
        return $this->belongsTo(Unit::class, 'dari_unit_id');
    }

    public function tujuanUnit()
    {
        return $this->belongsTo(Unit::class, 'tujuan_unit_id');
    }
}