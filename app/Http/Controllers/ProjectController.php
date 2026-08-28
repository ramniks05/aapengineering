<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::query()
            ->published()
            ->with('city')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->latest();

        if ($request->filled('status') && in_array($request->status, ['upcoming', 'ongoing', 'completed'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('city')) {
            $query->where('city_id', $request->city);
        }

        if ($request->filled('q')) {
            $term = '%'.$request->q.'%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('short_description', 'like', $term)
                    ->orWhere('project_type', 'like', $term)
                    ->orWhere('client_name', 'like', $term);
            });
        }

        $projects = $query->paginate(9)->withQueryString();
        $cities = City::query()->where('is_active', true)->orderBy('name')->get();

        return view('projects.index', compact('projects', 'cities'));
    }

    public function show(string $slug): View
    {
        $project = Project::query()
            ->published()
            ->with(['city', 'media'])
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Project::query()
            ->published()
            ->with('city')
            ->where('id', '!=', $project->id)
            ->when($project->city_id, fn ($q) => $q->where('city_id', $project->city_id))
            ->latest()
            ->take(3)
            ->get();

        return view('projects.show', compact('project', 'related'));
    }
}
