@extends('layouts.admin')

@section('title', 'Enquiry detail')
@section('heading', 'Enquiry detail')

@section('actions')
    <a href="{{ route('admin.enquiries.index') }}" class="btn btn-secondary">Back</a>
@endsection

@section('content')
<div class="panel" style="max-width:720px;">
    <p><strong>Name:</strong> {{ $enquiry->name }}</p>
    <p><strong>Phone:</strong> {{ $enquiry->phone }}</p>
    <p><strong>Email:</strong> {{ $enquiry->email ?: '—' }}</p>
    <p><strong>City:</strong> {{ $enquiry->city ?: '—' }}</p>
    <p><strong>Interest:</strong> {{ $enquiry->project_interest ?: '—' }}</p>
    <p><strong>Received:</strong> {{ $enquiry->created_at->format('d M Y H:i') }}</p>
    <hr style="border:0;border-top:1px solid var(--line);margin:1rem 0;">
    <p style="white-space:pre-line;">{{ $enquiry->message }}</p>

    <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}" onsubmit="return confirm('Delete this enquiry?')" style="margin-top:1.25rem;">
        @csrf
        @method('DELETE')
        <button class="btn btn-secondary" type="submit" style="color:#8a1f1f;">Delete enquiry</button>
    </form>
</div>
@endsection
