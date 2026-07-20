<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manga;
use App\Models\ShareLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShareLinkController extends Controller
{
    public function index(Manga $manga): View
    {
        $links = $manga->shareLinks()->withCount('views')->get();

        return view('admin.links.index', compact('manga', 'links'));
    }

    public function store(Request $request, Manga $manga): RedirectResponse
    {
        if (! $manga->is_published) {
            return back()->with('error', 'Publish the manga before creating share links.');
        }

        $data = $request->validate([
            'label' => ['nullable', 'string', 'max:255'],
        ]);

        $manga->shareLinks()->create([
            'label' => $data['label'] ?? null,
            'token' => ShareLink::generateToken(),
            'is_active' => true,
        ]);

        return back()->with('success', 'Share link created.');
    }

    public function show(Manga $manga, ShareLink $link): View
    {
        abort_unless($link->manga_id === $manga->id, 404);

        $link->loadCount('views');
        $countryStats = $link->countryStats();
        $recentViews = $link->views()->latest('viewed_at')->limit(50)->get();

        return view('admin.links.show', compact('manga', 'link', 'countryStats', 'recentViews'));
    }

    public function update(Request $request, Manga $manga, ShareLink $link): RedirectResponse
    {
        abort_unless($link->manga_id === $manga->id, 404);

        $data = $request->validate([
            'label' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $link->update([
            'label' => array_key_exists('label', $data) ? $data['label'] : $link->label,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Share link updated.');
    }

    public function countries(Manga $manga, ShareLink $link)
    {
        abort_unless($link->manga_id === $manga->id, 404);

        return response()->json([
            'link_id' => $link->id,
            'label' => $link->label,
            'views_count' => $link->views_count,
            'countries' => $link->countryStats()->map(fn ($row) => [
                'country_code' => $row->country_code,
                'country_name' => $row->country_name,
                'views' => (int) $row->views,
            ])->values(),
        ]);
    }

    public function destroy(Manga $manga, ShareLink $link): RedirectResponse
    {
        abort_unless($link->manga_id === $manga->id, 404);

        $link->delete();

        return redirect()
            ->route('admin.mangas.links.index', $manga)
            ->with('success', 'Share link deleted.');
    }

    public function regenerate(Manga $manga, ShareLink $link): RedirectResponse
    {
        abort_unless($link->manga_id === $manga->id, 404);

        $link->update([
            'token' => ShareLink::generateToken(),
        ]);

        return back()->with('success', 'Share link token regenerated.');
    }
}
