@extends('layouts.admin')

@section('title', 'Cities')
@section('heading', 'Cities')

@section('content')
<div class="split">
    <div class="panel">
        <h2>Add city</h2>
        <form method="POST" action="{{ route('admin.cities.store') }}" class="form-grid">
            @csrf
            <div class="form-field">
                <label>Name *</label>
                <input name="name" required>
            </div>
            <div class="form-field">
                <label>State</label>
                <input name="state">
            </div>
            <div class="form-field full">
                <label class="checkbox-row"><input type="checkbox" name="is_active" value="1" checked> Active</label>
            </div>
            <div class="form-field full">
                <button class="btn btn-primary" type="submit">Add city</button>
            </div>
        </form>
    </div>

    <div class="panel">
        @foreach($cities as $city)
            <div style="display:grid;grid-template-columns:1fr 1fr auto auto;gap:.75rem;align-items:end;padding:.85rem 0;border-bottom:1px solid var(--line);">
                <form method="POST" action="{{ route('admin.cities.update', $city) }}" style="display:contents;">
                    @csrf
                    @method('PUT')
                    <div class="form-field">
                        <label>Name</label>
                        <input name="name" value="{{ $city->name }}" required>
                    </div>
                    <div class="form-field">
                        <label>State</label>
                        <input name="state" value="{{ $city->state }}">
                    </div>
                    <div class="form-field">
                        <label class="checkbox-row"><input type="checkbox" name="is_active" value="1" @checked($city->is_active)> Active</label>
                        <button class="btn btn-secondary" type="submit">Save</button>
                    </div>
                </form>
                <form method="POST" action="{{ route('admin.cities.destroy', $city) }}" onsubmit="return confirm('Delete city?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:none;border:0;color:#8a1f1f;cursor:pointer;padding:.8rem 0;font:inherit;">Delete</button>
                </form>
            </div>
        @endforeach
        <div class="pagination">{{ $cities->links('pagination.simple') }}</div>
    </div>
</div>
@endsection
