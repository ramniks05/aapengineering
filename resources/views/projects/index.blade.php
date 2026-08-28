@extends('layouts.app')

@section('title', 'Projects | AAP Engineerings')

@section('content')
<div class="container page-hero">
    <h1>Projects</h1>
    <p class="lede">Filter by status, city or keyword to explore our electrical project portfolio.</p>
</div>

<section class="section" style="padding-top:0;">
    <div class="container">
        <form method="GET" action="{{ route('projects.index') }}" class="filters">
            <div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search projects">
            </div>
            <div>
                <select name="status">
                    <option value="">All statuses</option>
                    @foreach(['upcoming' => 'Upcoming', 'ongoing' => 'Ongoing', 'completed' => 'Completed'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="city">
                    <option value="">All cities</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" @selected((string) request('city') === (string) $city->id)>
                            {{ $city->name }}{{ $city->state ? ', '.$city->state : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="flex:0 0 auto; display:flex; gap:.5rem;">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn btn-secondary" href="{{ route('projects.index') }}">Reset</a>
            </div>
        </form>

        <div class="grid-3">
            @forelse($projects as $project)
                @include('partials.project-card', ['project' => $project])
            @empty
                <div class="panel" style="grid-column:1/-1;">
                    <p>No projects match your filters.</p>
                </div>
            @endforelse
        </div>

        <div class="pagination">
            {{ $projects->links('pagination.simple') }}
        </div>
    </div>
</section>
@endsection
