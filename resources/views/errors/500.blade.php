@extends('layouts.error')
@section('title', '500 - Internal Server Error')

@section('content')
    <div class="container">
        <div class="row vh-100 d-flex align-items-center">
            <div class="col-12 text-center">
                <img src="{{ asset('assets/images/error/error-page.png') }}" style="height: 50vh;" />
                <h1 class="display-4 mt-3">500 - Internal Server Error</h1>
                <p class="lead">Oops! Something went wrong on our end. Please try again later.</p>
            </div>
        </div>
    </div>
@endsection
