<a href="{{ route('projects.show', $project->slug) }}" class="project-card">
    <div class="thumb" style="background-image:url('{{ $project->cover_image_url ?: 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?auto=format&fit=crop&w=1000&q=80' }}')"></div>
    <div class="body">
        <div class="meta">
            <span class="badge {{ $project->status }}">{{ $project->statusLabel() }}</span>
            @if($project->city)
                <span class="badge">{{ $project->city->name }}</span>
            @endif
        </div>
        <h3>{{ $project->title }}</h3>
        <p>{{ \Illuminate\Support\Str::limit($project->short_description, 110) }}</p>
    </div>
</a>
