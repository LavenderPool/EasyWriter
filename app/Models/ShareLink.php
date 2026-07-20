<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ShareLink extends Model
{
    protected $fillable = [
        'manga_id',
        'token',
        'label',
        'views_count',
        'is_active',
        'last_viewed_at',
    ];

    protected function casts(): array
    {
        return [
            'views_count' => 'integer',
            'is_active' => 'boolean',
            'last_viewed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ShareLink $link): void {
            if (blank($link->token)) {
                $link->token = static::generateToken();
            }
        });
    }

    public static function generateToken(): string
    {
        do {
            $token = Str::random(48);
        } while (static::where('token', $token)->exists());

        return $token;
    }

    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(LinkView::class);
    }

    public function publicUrl(): string
    {
        return route('reader.show', $this->token);
    }

    public function countryStats()
    {
        return $this->views()
            ->selectRaw('country_code, country_name, COUNT(*) as views')
            ->groupBy('country_code', 'country_name')
            ->orderByDesc('views')
            ->get();
    }
}
