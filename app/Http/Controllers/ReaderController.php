<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\ShareLink;
use App\Services\GeoIpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReaderController extends Controller
{
    public function show(Request $request, string $token, GeoIpService $geoIp): View
    {
        $link = $this->resolveActiveLink($token);
        $manga = $link->manga()->with(['pages' => fn ($q) => $q->orderBy('page_number')])->firstOrFail();

        abort_unless($manga->is_published, 404);
        abort_if($manga->pages->isEmpty(), 404, 'This book has no pages yet.');

        $this->trackView($request, $link, $geoIp);

        $pages = $manga->pages->map(fn (Page $page) => [
            'id' => $page->id,
            'number' => $page->page_number,
            'url' => route('reader.page', [$token, $page->id]),
        ])->values();

        return view('reader.show', [
            'manga' => $manga,
            'link' => $link,
            'pages' => $pages,
            'totalPages' => $pages->count(),
            'progressKey' => 'ew_progress_'.$manga->id.'_'.$token,
        ]);
    }

    public function page(string $token, Page $page): StreamedResponse
    {
        $link = $this->resolveActiveLink($token);
        abort_unless($page->manga_id === $link->manga_id, 404);
        abort_unless($link->manga->is_published, 404);
        abort_unless(Storage::disk('local')->exists($page->image_path), 404);

        return Storage::disk('local')->response($page->image_path, null, [
            'Cache-Control' => 'private, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function resolveActiveLink(string $token): ShareLink
    {
        $link = ShareLink::where('token', $token)
            ->where('is_active', true)
            ->first();

        abort_unless($link, 404);

        return $link;
    }

    private function trackView(Request $request, ShareLink $link, GeoIpService $geoIp): void
    {
        $sessionKey = 'viewed_link_'.$link->id;

        if ($request->session()->has($sessionKey)) {
            return;
        }

        $ip = $request->ip() ?? '0.0.0.0';
        $geo = $geoIp->lookup($ip);

        $link->views()->create([
            'country_code' => $geo['code'],
            'country_name' => $geo['name'],
            'ip_hash' => hash('sha256', $ip.config('app.key')),
            'user_agent' => substr((string) $request->userAgent(), 0, 512),
            'viewed_at' => now(),
        ]);

        $link->increment('views_count');
        $link->update(['last_viewed_at' => now()]);

        $request->session()->put($sessionKey, true);
    }
}
