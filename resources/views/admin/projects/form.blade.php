@extends('layouts.admin')

@section('title', $project->exists ? 'Edit project' : 'Add project')
@section('heading', $project->exists ? 'Edit project' : 'Add project')

@section('actions')
    <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Back</a>
@endsection

@section('content')
<div class="split">
    <div class="panel">
        <form method="POST" action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}" class="form-grid">
            @csrf
            @if($project->exists)
                @method('PUT')
            @endif

            <div class="form-field full">
                <label>Title *</label>
                <input name="title" value="{{ old('title', $project->title) }}" required>
            </div>
            <div class="form-field">
                <label>Slug</label>
                <input name="slug" value="{{ old('slug', $project->slug) }}" placeholder="auto-generated if empty">
            </div>
            <div class="form-field">
                <label>Status *</label>
                <select name="status" required>
                    @foreach(['upcoming','ongoing','completed'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $project->status) === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-field">
                <label>City</label>
                <select name="city_id">
                    <option value="">— Select —</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" @selected((string) old('city_id', $project->city_id) === (string) $city->id)>{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-field">
                <label>Client name</label>
                <input name="client_name" value="{{ old('client_name', $project->client_name) }}">
            </div>
            <div class="form-field">
                <label>Project type</label>
                <input name="project_type" value="{{ old('project_type', $project->project_type) }}">
            </div>
            <div class="form-field">
                <label>Start date</label>
                <input type="date" name="start_date" value="{{ old('start_date', optional($project->start_date)->format('Y-m-d')) }}">
            </div>
            <div class="form-field">
                <label>End date</label>
                <input type="date" name="end_date" value="{{ old('end_date', optional($project->end_date)->format('Y-m-d')) }}">
            </div>
            <div class="form-field full">
                <label>Cover image URL (CDN)</label>
                <input name="cover_image_url" value="{{ old('cover_image_url', $project->cover_image_url) }}" placeholder="https://cdn.example.com/cover.jpg">
            </div>
            <div class="form-field full">
                <label>Short description</label>
                <textarea name="short_description" rows="3">{{ old('short_description', $project->short_description) }}</textarea>
            </div>
            <div class="form-field full">
                <label>Full description</label>
                <textarea name="description" rows="8">{{ old('description', $project->description) }}</textarea>
            </div>
            <div class="form-field">
                <label>Sort order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $project->sort_order ?? 0) }}">
            </div>
            <div class="form-field" style="justify-content:end;">
                <label class="checkbox-row"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $project->is_featured))> Featured on home</label>
                <label class="checkbox-row"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $project->is_published ?? true))> Published</label>
            </div>
            <div class="form-field full">
                <button class="btn btn-primary" type="submit">{{ $project->exists ? 'Update project' : 'Create project' }}</button>
            </div>
        </form>
    </div>

    <div>
        @if($project->exists)
            <div class="panel" style="margin-bottom:1rem;">
                <h2>Add media</h2>
                <p style="color:var(--muted);font-size:.92rem;">Images: CDN URL · Videos: CDN URL or YouTube link</p>
                <form method="POST" action="{{ route('admin.projects.media.store', $project) }}" class="form-grid">
                    @csrf
                    <div class="form-field full">
                        <label>Type</label>
                        <select name="type" required>
                            <option value="image">Image (CDN)</option>
                            <option value="video_cdn">Video (CDN)</option>
                            <option value="video_youtube">Video (YouTube)</option>
                        </select>
                    </div>
                    <div class="form-field full">
                        <label>URL *</label>
                        <input name="url" required placeholder="CDN image/video URL or YouTube link">
                    </div>
                    <div class="form-field full">
                        <label>Thumbnail URL (optional, useful for CDN video)</label>
                        <input name="thumbnail_url" placeholder="https://cdn.example.com/thumb.jpg">
                    </div>
                    <div class="form-field">
                        <label>Caption</label>
                        <input name="caption">
                    </div>
                    <div class="form-field">
                        <label>Sort order</label>
                        <input type="number" name="sort_order" min="0">
                    </div>
                    <div class="form-field full">
                        <button class="btn btn-accent" type="submit">Add media</button>
                    </div>
                </form>
            </div>

            <div class="panel">
                <h2>Media list</h2>
                <table class="table">
                    <thead>
                    <tr><th>Type</th><th>URL / caption</th><th></th></tr>
                    </thead>
                    <tbody>
                    @forelse($project->media as $media)
                        <tr>
                            <td>{{ $media->typeLabel() }}</td>
                            <td>
                                <div style="word-break:break-all;">{{ \Illuminate\Support\Str::limit($media->url, 60) }}</div>
                                @if($media->caption)<small>{{ $media->caption }}</small>@endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.projects.media.destroy', [$project, $media]) }}" onsubmit="return confirm('Remove media?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none;border:0;color:#8a1f1f;cursor:pointer;padding:0;font:inherit;">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3">No media yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="panel">
                <p>Save the project first, then add CDN images and CDN / YouTube videos.</p>
            </div>
        @endif
    </div>
</div>
@endsection
