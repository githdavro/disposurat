<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['nama_unit', 'kode_unit'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function suratMasuk()
    {
        return $this->hasMany(Surat::class, 'tujuan_unit_id');
    }

    public function suratKeluar()
    {
        return $this->hasMany(Surat::class, 'asal_unit_id');
    }
}