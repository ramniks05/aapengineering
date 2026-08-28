<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Enquiry;
use App\Models\GalleryItem;
use App\Models\Update;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $clients = Client::active()->orderBy('sort_order')->orderBy('name')->get();

        return view('about', compact('clients'));
    }

    public function services(): View
    {
        return view('services');
    }

    public function clients(): View
    {
        $clients = Client::active()->orderBy('sort_order')->orderBy('name')->get();

        return view('clients', compact('clients'));
    }

    public function gallery(Request $request): View
    {
        $query = GalleryItem::active()->orderBy('sort_order')->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $categories = GalleryItem::active()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('gallery', [
            'items' => $query->paginate(16)->withQueryString(),
            'categories' => $categories,
        ]);
    }

    public function updates(): View
    {
        $updates = Update::published()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9);

        return view('updates.index', compact('updates'));
    }

    public function updateShow(string $slug): View
    {
        $update = Update::published()->where('slug', $slug)->firstOrFail();

        $more = Update::published()
            ->where('id', '!=', $update->id)
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return view('updates.show', compact('update', 'more'));
    }

    public function contact(): View
    {
        return view('contact');
    }

    public function contactStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:120'],
            'city' => ['nullable', 'string', 'max:120'],
            'project_interest' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        Enquiry::create($data);

        return redirect()
            ->route('contact')
            ->with('success', 'Thank you. Your message has been received. We will contact you soon.');
    }
}
