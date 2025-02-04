@extends('vizsga.layouts.app')

@section('title', 'Filmek')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Filmek</h1>
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($shows as $show)
        <div class="col">
            <div class="card h-100">
                <img src="{{ asset('uploads/vizsga/' . $show->image_url) }}" class="card-img-top" alt="{{ $show->title }}"
                    style="height: 300px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $show->title }}</h5>
                    <p class="card-text">{{ $show->description }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="badge text-bg-primary">{{ $show->category }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
