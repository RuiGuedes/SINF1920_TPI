@extends('app')

@section('title', 'Layout')

@section('body')
    @include('partials.mainNavbar')

    <div class="container-fluid py-5 px-4 text-center">
        <img src="{{asset('img/layout.png')}}" class="img-fluid" alt="Layout Image">
    </div>
@endsection