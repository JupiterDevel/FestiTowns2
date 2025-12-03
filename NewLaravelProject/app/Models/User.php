<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'locality_id',
        'rank',
        'points',
        'last_login_at',
        'google_id',
        'accepted_legal',
    ];

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
            'last_login_at' => 'datetime',
            'accepted_legal' => 'boolean',
        ];
    }

    public function locality(): BelongsTo
    {
        return $this->belongsTo(Locality::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTownHall(): bool
    {
        return $this->role === 'townhall';
    }

    public function isVisitor(): bool
    {
        return $this->role === 'visitor';
    }

    public function addPoints(int $points): void
    {
        $this->points += $points;
        $this->updateRank();
        $this->save();
    }

    public function updateRank(): void
    {
        $oldRank = $this->rank;
        
        if ($this->points >= 500) {
            $this->rank = 'gold';
        } elseif ($this->points >= 200) {
            $this->rank = 'silver';
        } else {
            $this->rank = 'bronze';
        }
        
        // Si el rango cambió, podríamos añadir lógica adicional aquí
        if ($oldRank !== $this->rank) {
            // El usuario ha subido de rango
        }
    }

    public function getRankDisplayName(): string
    {
        return match($this->rank) {
            'gold' => 'Oro',
            'silver' => 'Plata',
            'bronze' => 'Bronce',
            default => 'Bronce',
        };
    }

    public function getRankIcon(): string
    {
        return match($this->rank) {
            'gold' => '🥇',
            'silver' => '🥈',
            'bronze' => '🥉',
            default => '🥉',
        };
    }

    public function canEarnLoginPoints(): bool
    {
        if (!$this->last_login_at) {
            return true;
        }
        
        return !$this->last_login_at->isToday();
    }

    public function canEarnVisitPoints(Festivity $festivity): bool
    {
        // Solo visitantes pueden ganar puntos por visitas
        if (!$this->isVisitor()) {
            return false;
        }
        
        // No puede ganar puntos por festividades de su propia localidad
        if ($this->locality_id && $this->locality_id === $festivity->locality_id) {
            return false;
        }
        
        // Verificar si ya visitó esta festividad hoy
        $today = now()->toDateString();
        $visitKey = "visit_{$festivity->id}_{$today}";
        
        return !session()->has($visitKey);
    }

    public function markVisitedToday(Festivity $festivity): void
    {
        $today = now()->toDateString();
        $visitKey = "visit_{$festivity->id}_{$today}";
        session()->put($visitKey, true);
    }
}
