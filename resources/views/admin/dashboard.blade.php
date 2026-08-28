@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
<div class="stats" style="margin:0 0 1.5rem;grid-template-columns:repeat(4,1fr);">
    <div class="stat"><strong>{{ $counts['upcoming'] }}</strong><span>Upcoming</span></div>
    <div class="stat"><strong>{{ $counts['ongoing'] }}</strong><span>Ongoing</span></div>
    <div class="stat"><strong>{{ $counts['completed'] }}</strong><span>Completed</span></div>
    <div class="stat"><strong>{{ $counts['enquiries'] }}</strong><span>Unread enquiries</span></div>
</div>
<div class="stats" style="margin:0 0 1.5rem;">
    <div class="stat"><strong>{{ $counts['clients'] }}</strong><span>Clients</span></div>
    <div class="stat"><strong>{{ $counts['gallery'] }}</strong><span>Gallery items</span></div>
    <div class="stat"><strong>{{ $counts['updates'] }}</strong><span>Updates</span></div>
</div>

<div class="split">
    <div class="panel">
        <h2>Recent projects</h2>
        <table class="table">
            <thead>
            <tr><th>Title</th><th>Status</th><th>City</th></tr>
            </thead>
            <tbody>
            @forelse($recentProjects as $project)
                <tr>
                    <td><a href="{{ route('admin.projects.edit', $project) }}">{{ $project->title }}</a></td>
                    <td>{{ $project->statusLabel() }}</td>
                    <td>{{ $project->city?->name ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No projects yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="panel">
        <h2>Recent enquiries</h2>
        <table class="table">
            <thead>
            <tr><th>Name</th><th>Phone</th><th></th></tr>
            </thead>
            <tbody>
            @forelse($recentEnquiries as $enquiry)
                <tr>
                    <td>{{ $enquiry->name }} @unless($enquiry->is_read)<span class="badge upcoming">New</span>@endunless</td>
                    <td>{{ $enquiry->phone }}</td>
                    <td><a href="{{ route('admin.enquiries.show', $enquiry) }}">View</a></td>
                </tr>
            @empty
                <tr><td colspan="3">No enquiries yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
