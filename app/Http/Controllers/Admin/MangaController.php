<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manga;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MangaController extends Controller
{
    public function index(): View
    {
        $mangas = Manga::withCount(['pages', 'shareLinks'])
            ->latest()
            ->paginate(20);

        return view('admin.mangas.index', compact('mangas'));
    }

    public function create(): View
    {
        return view('admin.mangas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'cover' => ['nullable', 'image', 'max:5120'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        $manga = Manga::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store("mangas/{$manga->id}", 'local');
            $manga->update(['cover_path' => $path]);
        }

        return redirect()
            ->route('admin.mangas.show', $manga)
            ->with('success', __('admin.mangas.created'));
    }

    public function show(Manga $manga): View
    {
        $manga->load(['pages', 'shareLinks']);

        return view('admin.mangas.show', compact('manga'));
    }

    public function edit(Manga $manga): View
    {
        return view('admin.mangas.edit', compact('manga'));
    }

    public function update(Request $request, Manga $manga): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'cover' => ['nullable', 'image', 'max:5120'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        $manga->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        if ($request->hasFile('cover')) {
            if ($manga->cover_path) {
                Storage::disk('local')->delete($manga->cover_path);
            }

            $path = $request->file('cover')->store("mangas/{$manga->id}", 'local');
            $manga->update(['cover_path' => $path]);
        }

        return redirect()
            ->route('admin.mangas.show', $manga)
            ->with('success', __('admin.mangas.updated'));
    }

    public function destroy(Manga $manga): RedirectResponse
    {
        foreach ($manga->pages as $page) {
            $page->delete();
        }

        if ($manga->cover_path) {
            Storage::disk('local')->delete($manga->cover_path);
        }

        Storage::disk('local')->deleteDirectory("mangas/{$manga->id}");
        $manga->delete();

        return redirect()
            ->route('admin.mangas.index')
            ->with('success', __('admin.mangas.deleted'));
    }
}
