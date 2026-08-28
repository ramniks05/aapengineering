<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Update;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UpdateController extends Controller
{
    public function index(): View
    {
        return view('admin.updates.index', [
            'updates' => Update::orderByDesc('published_at')->orderByDesc('id')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.updates.form', [
            'update' => new Update([
                'is_published' => true,
                'published_at' => now(),
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Update::create($this->validated($request));

        return redirect()->route('admin.updates.index')->with('success', 'Update published.');
    }

    public function edit(Update $update): View
    {
        return view('admin.updates.form', compact('update'));
    }

    public function update(Request $request, Update $update): RedirectResponse
    {
        $update->update($this->validated($request, $update));

        return back()->with('success', 'Update saved.');
    }

    public function destroy(Update $update): RedirectResponse
    {
        $update->delete();

        return redirect()->route('admin.updates.index')->with('success', 'Update deleted.');
    }

    private function validated(Request $request, ?Update $update = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'cover_image_url' => ['nullable', 'string', 'max:1000'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['published_at'] ?? now();

        if (blank($data['slug'] ?? null)) {
            $data['slug'] = Update::uniqueSlug($data['title'], $update?->id);
        } else {
            $data['slug'] = Update::uniqueSlug($data['slug'], $update?->id);
        }

        return $data;
    }
}
