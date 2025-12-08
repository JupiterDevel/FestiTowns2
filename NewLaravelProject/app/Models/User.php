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
        'province',
        'photo',
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

    /**
     * Get the user's photo URL or generate initials avatar
     */
    public function getPhotoUrl(): string
    {
        if ($this->photo) {
            return filter_var($this->photo, FILTER_VALIDATE_URL) 
                ? $this->photo 
                : url($this->photo);
        }
        
        // Return data URI for initials avatar
        return $this->getInitialsAvatar();
    }

    /**
     * Get user's initials (e.g., "JM" for "Juan Martinez")
     */
    public function getInitials(): string
    {
        $name = trim($this->name);
        $parts = explode(' ', $name);
        
        if (count($parts) >= 2) {
            // First letter of first name + first letter of last name
            return strtoupper(substr($parts[0], 0, 1) . substr($parts[count($parts) - 1], 0, 1));
        } elseif (count($parts) == 1 && strlen($parts[0]) >= 2) {
            // If only one word, take first two letters
            return strtoupper(substr($parts[0], 0, 2));
        } else {
            // Fallback: first letter only
            return strtoupper(substr($name, 0, 1));
        }
    }

    /**
     * Generate a consistent color based on user's name (for avatar background)
     */
    public function getAvatarColor(): string
    {
        $colors = [
            '#667eea', '#764ba2', '#f093fb', '#4facfe', '#00f2fe',
            '#43e97b', '#fa709a', '#fee140', '#30cfd0', '#a8edea',
            '#ff9a9e', '#fecfef', '#fecfef', '#ffecd2', '#fcb69f',
            '#ff8a80', '#ea4c89', '#8e2de2', '#4a00e0', '#00d2ff'
        ];
        
        $hash = crc32($this->name);
        return $colors[abs($hash) % count($colors)];
    }

    /**
     * Generate SVG data URI for initials avatar (Google style)
     */
    public function getInitialsAvatar(): string
    {
        $initials = $this->getInitials();
        $color = $this->getAvatarColor();
        
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">';
        $svg .= '<rect width="200" height="200" fill="' . htmlspecialchars($color) . '"/>';
        $svg .= '<text x="50%" y="50%" font-family="Arial, sans-serif" font-size="80" font-weight="500" fill="white" text-anchor="middle" dominant-baseline="central">';
        $svg .= htmlspecialchars($initials);
        $svg .= '</text>';
        $svg .= '</svg>';
        
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
