@extends('layouts.app')

@section('title', 'Updates | AAP Engineerings')

@section('content')
<div class="container page-hero">
    <h1>Updates</h1>
    <p class="lede">Company news, milestones and project announcements.</p>
</div>

<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="grid-3">
            @forelse($updates as $update)
                @include('partials.update-card', ['update' => $update])
            @empty
                <div class="panel" style="grid-column:1/-1;"><p>No updates published yet.</p></div>
            @endforelse
        </div>
        <div class="pagination">{{ $updates->links('pagination.simple') }}</div>
    </div>
</section>
@endsection
