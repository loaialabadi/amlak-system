<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = ['name', 'email', 'password', 'role', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['is_active' => 'boolean'];

    const ROLE_ADMIN  = 'admin';
    const ROLE_EDITOR = 'editor';
    const ROLE_VIEWER = 'viewer';

    public static array $roleLabels = [
        self::ROLE_ADMIN  => 'مدير النظام',
        self::ROLE_EDITOR => 'محرر',
        self::ROLE_VIEWER => 'مشاهد',
    ];

    public function isAdmin(): bool  { return $this->role === self::ROLE_ADMIN; }
    public function isEditor(): bool { return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_EDITOR]); }

    public function getRoleLabelAttribute(): string
    {
        return self::$roleLabels[$this->role] ?? $this->role;
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'issued_by');
    }

    public function salesCreated(): HasMany
    {
        return $this->hasMany(Sale::class, 'created_by');
    }
}
