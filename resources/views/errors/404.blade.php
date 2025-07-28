@extends('layouts.error')
@section('title', '404 - Page Not Found')

@section('content')
    <div class="container">
        <div class="row vh-100 d-flex align-items-center">
            <div class="col-12 text-center">
                <img src="{{ asset('assets/images/error/error-page.png') }}" style="height: 50vh;" />
                <h1 class="display-4 mt-3">404 - Page Not Found</h1>
                <p class="lead">Sorry, the page you are looking for could not be found.</p>
            </div>
        </div>
    </div>
@endsection
