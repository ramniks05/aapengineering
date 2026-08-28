@extends('layouts.app')

@section('title', 'Gallery | AAP Engineerings')

@section('content')
<div class="container page-hero">
    <h1>Gallery</h1>
    <p class="lede">Project visuals, site progress and video documentation.</p>
</div>

<section class="section" style="padding-top:0;">
    <div class="container">
        <form method="GET" class="filters">
            <div>
                <select name="category" onchange="this.form.submit()">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="gallery-masonry">
            @forelse($items as $item)
                @include('partials.gallery-tile', ['item' => $item])
            @empty
                <div class="panel">No gallery items yet.</div>
            @endforelse
        </div>

        <div class="pagination">{{ $items->links('pagination.simple') }}</div>
    </div>
</section>
@endsection
