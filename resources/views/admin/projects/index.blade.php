@extends('layouts.admin')

@section('title', 'Projects')
@section('heading', 'Projects')

@section('actions')
    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">Add project</a>
@endsection

@section('content')
<form method="GET" class="filters">
    <div>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search title / client">
    </div>
    <div>
        <select name="status">
            <option value="">All statuses</option>
            @foreach(['upcoming','ongoing','completed'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div>
    <div style="flex:0 0 auto;">
        <button class="btn btn-secondary" type="submit">Filter</button>
    </div>
</form>

<div class="panel">
    <table class="table">
        <thead>
        <tr>
            <th>Title</th>
            <th>Status</th>
            <th>City</th>
            <th>Published</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($projects as $project)
            <tr>
                <td>{{ $project->title }}</td>
                <td><span class="badge {{ $project->status }}">{{ $project->statusLabel() }}</span></td>
                <td>{{ $project->city?->name ?? '—' }}</td>
                <td>{{ $project->is_published ? 'Yes' : 'No' }}</td>
                <td style="white-space:nowrap;">
                    <a href="{{ route('admin.projects.edit', $project) }}">Edit</a>
                    ·
                    <a href="{{ route('projects.show', $project->slug) }}" target="_blank">View</a>
                    ·
                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this project?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:none;border:0;color:#8a1f1f;cursor:pointer;padding:0;font:inherit;">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No projects found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="pagination">{{ $projects->links('pagination.simple') }}</div>
</div>
@endsection
