<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Project;
use App\Models\ProjectMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::query()->with('city')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $term = '%'.$request->q.'%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('client_name', 'like', $term);
            });
        }

        return view('admin.projects.index', [
            'projects' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.form', [
            'project' => new Project(['status' => 'upcoming', 'is_published' => true]),
            'cities' => City::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $project = Project::create($data);

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Project created. Add CDN / YouTube media below.');
    }

    public function edit(Project $project): View
    {
        $project->load('media');

        return view('admin.projects.form', [
            'project' => $project,
            'cities' => City::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $project->update($this->validated($request, $project));

        return back()->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project deleted.');
    }

    public function storeMedia(Request $request, Project $project): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:image,video_cdn,video_youtube'],
            'url' => ['required', 'string', 'max:1000'],
            'thumbnail_url' => ['nullable', 'string', 'max:1000'],
            'caption' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? (($project->media()->max('sort_order') ?? 0) + 1);
        $project->media()->create($data);

        if ($data['type'] === 'image' && blank($project->cover_image_url)) {
            $project->update(['cover_image_url' => $data['url']]);
        }

        return back()->with('success', 'Media added.');
    }

    public function destroyMedia(Project $project, ProjectMedia $medium): RedirectResponse
    {
        abort_unless($medium->project_id === $project->id, 404);
        $medium->delete();

        return back()->with('success', 'Media removed.');
    }

    private function validated(Request $request, ?Project $project = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220'],
            'status' => ['required', 'in:upcoming,ongoing,completed'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'client_name' => ['nullable', 'string', 'max:180'],
            'project_type' => ['nullable', 'string', 'max:180'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'cover_image_url' => ['nullable', 'string', 'max:1000'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if (blank($data['slug'] ?? null)) {
            $data['slug'] = Project::uniqueSlug($data['title'], $project?->id);
        } else {
            $data['slug'] = Project::uniqueSlug($data['slug'], $project?->id);
        }

        return $data;
    }
}
