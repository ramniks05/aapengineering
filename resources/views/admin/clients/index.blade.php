@extends('layouts.admin')

@section('title', 'Clients')
@section('heading', 'Clients')

@section('content')
<div class="split">
    <div class="panel">
        <h2>Add client</h2>
        <form method="POST" action="{{ route('admin.clients.store') }}" class="form-grid">
            @csrf
            <div class="form-field full">
                <label>Name *</label>
                <input name="name" required>
            </div>
            <div class="form-field full">
                <label>Logo URL (CDN)</label>
                <input name="logo_url" placeholder="https://cdn.example.com/logo.png">
            </div>
            <div class="form-field">
                <label>Industry</label>
                <input name="industry">
            </div>
            <div class="form-field">
                <label>Website</label>
                <input name="website_url">
            </div>
            <div class="form-field">
                <label>Sort order</label>
                <input type="number" name="sort_order" value="0">
            </div>
            <div class="form-field">
                <label class="checkbox-row"><input type="checkbox" name="is_active" value="1" checked> Active</label>
            </div>
            <div class="form-field full">
                <button class="btn btn-primary" type="submit">Add client</button>
            </div>
        </form>
    </div>

    <div class="panel">
        @foreach($clients as $client)
            <div style="padding:1rem 0;border-bottom:1px solid var(--line);">
                <form method="POST" action="{{ route('admin.clients.update', $client) }}" class="form-grid">
                    @csrf
                    @method('PUT')
                    <div class="form-field">
                        <label>Name</label>
                        <input name="name" value="{{ $client->name }}" required>
                    </div>
                    <div class="form-field">
                        <label>Industry</label>
                        <input name="industry" value="{{ $client->industry }}">
                    </div>
                    <div class="form-field full">
                        <label>Logo URL</label>
                        <input name="logo_url" value="{{ $client->logo_url }}">
                    </div>
                    <div class="form-field">
                        <label>Website</label>
                        <input name="website_url" value="{{ $client->website_url }}">
                    </div>
                    <div class="form-field">
                        <label>Sort</label>
                        <input type="number" name="sort_order" value="{{ $client->sort_order }}">
                    </div>
                    <div class="form-field">
                        <label class="checkbox-row"><input type="checkbox" name="is_active" value="1" @checked($client->is_active)> Active</label>
                    </div>
                    <div class="form-field full" style="flex-direction:row;gap:1rem;align-items:center;">
                        <button class="btn btn-secondary" type="submit">Save</button>
                    </div>
                </form>
                <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" onsubmit="return confirm('Delete client?')" style="margin-top:.5rem;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:none;border:0;color:#8a1f1f;cursor:pointer;padding:0;font:inherit;">Delete</button>
                </form>
            </div>
        @endforeach
        <div class="pagination">{{ $clients->links('pagination.simple') }}</div>
    </div>
</div>
@endsection
