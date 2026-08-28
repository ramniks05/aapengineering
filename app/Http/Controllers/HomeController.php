<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\GalleryItem;
use App\Models\Project;
use App\Models\Update;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featured = Project::query()
            ->published()
            ->featured()
            ->with('city')
            ->latest()
            ->take(6)
            ->get();

        if ($featured->isEmpty()) {
            $featured = Project::query()
                ->published()
                ->with('city')
                ->latest()
                ->take(6)
                ->get();
        }

        $stats = [
            'completed' => Project::published()->where('status', 'completed')->count(),
            'ongoing' => Project::published()->where('status', 'ongoing')->count(),
            'upcoming' => Project::published()->where('status', 'upcoming')->count(),
            'clients' => Client::active()->count(),
        ];

        $clients = Client::active()->orderBy('sort_order')->orderBy('name')->take(12)->get();
        $gallery = GalleryItem::active()->orderBy('sort_order')->latest()->take(8)->get();
        $updates = Update::published()->orderByDesc('published_at')->orderByDesc('id')->take(3)->get();

        return view('home', compact('featured', 'stats', 'clients', 'gallery', 'updates'));
    }
}
