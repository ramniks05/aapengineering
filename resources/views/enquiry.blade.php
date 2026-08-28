@extends('layouts.app')

@section('title', 'Enquiry | AAP Engineerings')

@section('content')
<div class="container page-hero">
    <h1>Project enquiry</h1>
    <p class="lede">Tell us about your electrical project. Our team will get back to you.</p>
</div>

<section class="section" style="padding-top:0;">
    <div class="container" style="max-width:760px;">
        <div class="panel">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="margin:0; padding-left:1.1rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('enquiry.store') }}" class="form-grid">
                @csrf
                <div class="form-field">
                    <label for="name">Name *</label>
                    <input id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="form-field">
                    <label for="phone">Phone *</label>
                    <input id="phone" name="phone" value="{{ old('phone') }}" required>
                </div>
                <div class="form-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}">
                </div>
                <div class="form-field">
                    <label for="city">City</label>
                    <input id="city" name="city" value="{{ old('city') }}">
                </div>
                <div class="form-field full">
                    <label for="project_interest">Project interest</label>
                    <input id="project_interest" name="project_interest" value="{{ old('project_interest', request('interest')) }}">
                </div>
                <div class="form-field full">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                </div>
                <div class="form-field full">
                    <button class="btn btn-primary" type="submit">Submit enquiry</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
