@extends('layouts.admin')

@section('title', 'Updates')
@section('heading', 'Updates')

@section('actions')
    <a href="{{ route('admin.updates.create') }}" class="btn btn-primary">Add update</a>
@endsection

@section('content')
<div class="panel">
    <table class="table">
        <thead>
        <tr>
            <th>Title</th>
            <th>Published</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($updates as $update)
            <tr>
                <td>{{ $update->title }}</td>
                <td>{{ optional($update->published_at)->format('d M Y') ?: '—' }}</td>
                <td>{{ $update->is_published ? 'Live' : 'Draft' }}</td>
                <td style="white-space:nowrap;">
                    <a href="{{ route('admin.updates.edit', $update) }}">Edit</a>
                    ·
                    <a href="{{ route('updates.show', $update->slug) }}" target="_blank">View</a>
                    ·
                    <form action="{{ route('admin.updates.destroy', $update) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete update?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:none;border:0;color:#8a1f1f;cursor:pointer;padding:0;font:inherit;">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">No updates yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="pagination">{{ $updates->links('pagination.simple') }}</div>
</div>
@endsection
