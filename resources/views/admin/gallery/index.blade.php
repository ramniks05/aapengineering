@extends('layouts.admin')

@section('title', 'Gallery')
@section('heading', 'Gallery')

@section('content')
<div class="split">
    <div class="panel">
        <h2>Add gallery item</h2>
        <p style="color:var(--muted);font-size:.92rem;">Images via CDN · Videos via CDN or YouTube</p>
        <form method="POST" action="{{ route('admin.gallery.store') }}" class="form-grid">
            @csrf
            <div class="form-field">
                <label>Title</label>
                <input name="title">
            </div>
            <div class="form-field">
                <label>Category</label>
                <input name="category" placeholder="Industrial / Commercial">
            </div>
            <div class="form-field full">
                <label>Type *</label>
                <select name="type" required>
                    <option value="image">Image (CDN)</option>
                    <option value="video_cdn">Video (CDN)</option>
                    <option value="video_youtube">Video (YouTube)</option>
                </select>
            </div>
            <div class="form-field full">
                <label>URL *</label>
                <input name="url" required>
            </div>
            <div class="form-field full">
                <label>Thumbnail URL</label>
                <input name="thumbnail_url">
            </div>
            <div class="form-field">
                <label>Caption</label>
                <input name="caption">
            </div>
            <div class="form-field">
                <label>Sort</label>
                <input type="number" name="sort_order" value="0">
            </div>
            <div class="form-field full">
                <label class="checkbox-row"><input type="checkbox" name="is_active" value="1" checked> Active</label>
            </div>
            <div class="form-field full">
                <button class="btn btn-primary" type="submit">Add item</button>
            </div>
        </form>
    </div>

    <div class="panel">
        <table class="table">
            <thead>
            <tr><th>Preview</th><th>Details</th><th></th></tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td>
                        @if($item->previewUrl())
                            <img src="{{ $item->previewUrl() }}" alt="" style="width:72px;height:52px;object-fit:cover;border-radius:8px;">
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        <strong>{{ $item->title ?: 'Untitled' }}</strong><br>
                        <small>{{ $item->type }} @if($item->category)· {{ $item->category }}@endif</small>
                        <div style="word-break:break-all;color:var(--muted);font-size:.82rem;">{{ \Illuminate\Support\Str::limit($item->url, 70) }}</div>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.gallery.destroy', $item) }}" onsubmit="return confirm('Delete item?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none;border:0;color:#8a1f1f;cursor:pointer;padding:0;font:inherit;">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3">No gallery items yet.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $items->links('pagination.simple') }}</div>
    </div>
</div>
@endsection
