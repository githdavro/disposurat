<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'unit_id', 'last_login_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function suratDikirim()
    {
        return $this->hasMany(Surat::class, 'pengirim_id');
    }

    public function disposisis()
    {
        return $this->hasMany(Disposisi::class, 'tujuan_unit_id');
    }

    // Tambahkan relasi notifikasis
    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class);
    }

    // Helper method untuk notifikasi belum dibaca
    public function unreadNotifications()
    {
        return $this->notifikasis()->where('dibaca', false);
    }

    // Helper method untuk surat masuk
    public function suratMasuk()
    {
        return $this->hasMany(Surat::class, 'tujuan_unit_id');
    }

    /**
     * Check if user has given role.
     * Supports either a "role" attribute or a roles() relation (many-to-many).
     */
    public function hasRole(string $role): bool
    {
        // If you have a roles() relation (e.g. roles table, pivot), check it:
        if (method_exists($this, 'roles')) {
            return $this->roles()->where('name', $role)->exists();
        }

        // If you store a single role in a column named "role":
        if (isset($this->role)) {
            return $this->role === $role;
        }

        return false;
    }
}