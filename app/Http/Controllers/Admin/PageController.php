<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manga;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PageController extends Controller
{
    public function index(Manga $manga): View
    {
        $pages = $manga->pages()->paginate(40);

        return view('admin.pages.index', compact('manga', 'pages'));
    }

    public function create(Manga $manga): View
    {
        $nextNumber = ((int) $manga->pages()->max('page_number')) + 1;

        return view('admin.pages.create', compact('manga', 'nextNumber'));
    }

    public function store(Request $request, Manga $manga): RedirectResponse
    {
        $data = $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', 'max:10240'],
            'start_number' => ['nullable', 'integer', 'min:1'],
        ]);

        $start = $data['start_number']
            ?? (((int) $manga->pages()->max('page_number')) + 1);

        DB::transaction(function () use ($request, $manga, $start) {
            $number = $start;

            foreach ($request->file('images') as $image) {
                while ($manga->pages()->where('page_number', $number)->exists()) {
                    $number++;
                }

                $path = $image->store("mangas/{$manga->id}/pages", 'local');

                $manga->pages()->create([
                    'page_number' => $number,
                    'image_path' => $path,
                    'original_name' => $image->getClientOriginalName(),
                ]);

                $number++;
            }
        });

        return redirect()
            ->route('admin.mangas.pages.index', $manga)
            ->with('success', 'Pages uploaded.');
    }

    public function edit(Manga $manga, Page $page): View
    {
        abort_unless($page->manga_id === $manga->id, 404);

        return view('admin.pages.edit', compact('manga', 'page'));
    }

    public function update(Request $request, Manga $manga, Page $page): RedirectResponse
    {
        abort_unless($page->manga_id === $manga->id, 404);

        $data = $request->validate([
            'page_number' => [
                'required',
                'integer',
                'min:1',
                'unique:pages,page_number,'.$page->id.',id,manga_id,'.$manga->id,
            ],
            'image' => ['nullable', 'image', 'max:10240'],
        ]);

        $page->page_number = $data['page_number'];

        if ($request->hasFile('image')) {
            Storage::disk('local')->delete($page->image_path);
            $page->image_path = $request->file('image')->store("mangas/{$manga->id}/pages", 'local');
            $page->original_name = $request->file('image')->getClientOriginalName();
        }

        $page->save();

        return redirect()
            ->route('admin.mangas.pages.index', $manga)
            ->with('success', 'Page updated.');
    }

    public function destroy(Manga $manga, Page $page): RedirectResponse
    {
        abort_unless($page->manga_id === $manga->id, 404);

        $page->delete();

        return redirect()
            ->route('admin.mangas.pages.index', $manga)
            ->with('success', 'Page deleted.');
    }

    public function reorder(Request $request, Manga $manga): RedirectResponse
    {
        $data = $request->validate([
            'order' => ['required', 'array', 'min:1'],
            'order.*' => ['required', 'integer', 'exists:pages,id'],
        ]);

        DB::transaction(function () use ($manga, $data) {
            foreach ($data['order'] as $index => $pageId) {
                Page::where('id', $pageId)
                    ->where('manga_id', $manga->id)
                    ->update(['page_number' => $index + 1]);
            }
        });

        return back()->with('success', 'Pages reordered.');
    }

    public function image(Manga $manga, Page $page): StreamedResponse
    {
        abort_unless($page->manga_id === $manga->id, 404);
        abort_unless(Storage::disk('local')->exists($page->image_path), 404);

        return Storage::disk('local')->response($page->image_path);
    }
}
