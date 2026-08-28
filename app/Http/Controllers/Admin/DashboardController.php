<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Enquiry;
use App\Models\GalleryItem;
use App\Models\Project;
use App\Models\Update;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'counts' => [
                'upcoming' => Project::where('status', 'upcoming')->count(),
                'ongoing' => Project::where('status', 'ongoing')->count(),
                'completed' => Project::where('status', 'completed')->count(),
                'enquiries' => Enquiry::where('is_read', false)->count(),
                'clients' => Client::count(),
                'gallery' => GalleryItem::count(),
                'updates' => Update::count(),
            ],
            'recentProjects' => Project::with('city')->latest()->take(5)->get(),
            'recentEnquiries' => Enquiry::latest()->take(5)->get(),
        ]);
    }
}
