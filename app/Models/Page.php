<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Page extends Model
{
    protected $fillable = [
        'manga_id',
        'page_number',
        'image_path',
        'original_name',
    ];

    protected function casts(): array
    {
        return [
            'page_number' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (Page $page): void {
            if ($page->image_path && Storage::disk('local')->exists($page->image_path)) {
                Storage::disk('local')->delete($page->image_path);
            }
        });
    }

    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class);
    }
}
