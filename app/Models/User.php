<?php

namespace App\Models;

use Filament\Panel;
use App\Support\AuthorizedDomains;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int id
 * @property string name
 * @property string email
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Post> posts
 */
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }

    public function isAdmin(): bool
    {
        $emailDomain = explode('@', $this->email)[1] ?? false;

        if (!$emailDomain) {
            return false;
        }

        return in_array($emailDomain, AuthorizedDomains::get()) && $this->hasVerifiedEmail();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

}
