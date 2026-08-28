@extends('layouts.admin')

@section('title', 'Enquiries')
@section('heading', 'Enquiries')

@section('content')
<div class="panel">
    <table class="table">
        <thead>
        <tr>
            <th>Date</th>
            <th>Name</th>
            <th>Phone</th>
            <th>City</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($enquiries as $enquiry)
            <tr>
                <td>{{ $enquiry->created_at->format('d M Y H:i') }}</td>
                <td>{{ $enquiry->name }}</td>
                <td>{{ $enquiry->phone }}</td>
                <td>{{ $enquiry->city ?: '—' }}</td>
                <td>{{ $enquiry->is_read ? 'Read' : 'New' }}</td>
                <td><a href="{{ route('admin.enquiries.show', $enquiry) }}">Open</a></td>
            </tr>
        @empty
            <tr><td colspan="6">No enquiries yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="pagination">{{ $enquiries->links('pagination.simple') }}</div>
</div>
@endsection
