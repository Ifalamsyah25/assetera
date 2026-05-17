<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'username', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_PIMPINAN = 'pimpinan';
    public const ROLE_STAFF = 'staff';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function roles(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_PIMPINAN,
            self::ROLE_STAFF,
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function adminlte_image(): string
    {
        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=FFFFFF&background=3c8dbc';
    }

    public function adminlte_desc(): string
    {
        return ucfirst($this->role ?? 'user');
    }

    public function adminlte_profile_url(): string
    {
        return 'profile.edit';
    }
}
