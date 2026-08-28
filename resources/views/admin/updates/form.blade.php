@extends('layouts.admin')

@section('title', $update->exists ? 'Edit update' : 'Add update')
@section('heading', $update->exists ? 'Edit update' : 'Add update')

@section('actions')
    <a href="{{ route('admin.updates.index') }}" class="btn btn-secondary">Back</a>
@endsection

@section('content')
<div class="panel" style="max-width:820px;">
    <form method="POST" action="{{ $update->exists ? route('admin.updates.update', $update) : route('admin.updates.store') }}" class="form-grid">
        @csrf
        @if($update->exists)
            @method('PUT')
        @endif

        <div class="form-field full">
            <label>Title *</label>
            <input name="title" value="{{ old('title', $update->title) }}" required>
        </div>
        <div class="form-field">
            <label>Slug</label>
            <input name="slug" value="{{ old('slug', $update->slug) }}">
        </div>
        <div class="form-field">
            <label>Published at</label>
            <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($update->published_at)->format('Y-m-d\TH:i')) }}">
        </div>
        <div class="form-field full">
            <label>Cover image URL (CDN)</label>
            <input name="cover_image_url" value="{{ old('cover_image_url', $update->cover_image_url) }}">
        </div>
        <div class="form-field full">
            <label>Excerpt</label>
            <textarea name="excerpt" rows="3">{{ old('excerpt', $update->excerpt) }}</textarea>
        </div>
        <div class="form-field full">
            <label>Body</label>
            <textarea name="body" rows="10">{{ old('body', $update->body) }}</textarea>
        </div>
        <div class="form-field full">
            <label class="checkbox-row"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $update->is_published ?? true))> Published</label>
        </div>
        <div class="form-field full">
            <button class="btn btn-primary" type="submit">{{ $update->exists ? 'Save update' : 'Create update' }}</button>
        </div>
    </form>
</div>
@endsection
