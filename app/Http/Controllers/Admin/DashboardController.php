<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LinkView;
use App\Models\Manga;
use App\Models\ShareLink;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'mangasCount' => Manga::count(),
            'publishedCount' => Manga::where('is_published', true)->count(),
            'linksCount' => ShareLink::count(),
            'viewsCount' => LinkView::count(),
            'recentMangas' => Manga::withCount(['pages', 'shareLinks'])
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }
}
