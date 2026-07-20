<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Manga extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_path',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Manga $manga): void {
            if (blank($manga->slug)) {
                $manga->slug = static::uniqueSlug($manga->title);
            }
        });
    }

    public static function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'manga';
        $slug = $base;
        $i = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class)->orderBy('page_number');
    }

    public function shareLinks(): HasMany
    {
        return $this->hasMany(ShareLink::class)->latest();
    }

    public function pagesCount(): int
    {
        return $this->pages()->count();
    }
}
