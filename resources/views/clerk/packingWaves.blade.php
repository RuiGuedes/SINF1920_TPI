@extends('app')

@section('title', 'Packing')

@section('body')
    @include('partials.mainNavbar')

    @include('partials.subNavbar', [
        'tabs' => [
            [
                'route' => 'picking-waves',
                'name' => 'Picking Waves'
            ],
            [
                'route' => 'packing-waves',
                'name' => 'Packing',
                'active' => true
            ],
            [
                'route' => 'dispatching',
                'name' => 'Dispatching'
            ]
        ]
    ])

    <div class="main-container container pt-5">
        @include('partials.tableHeader', [
            'clerk' => true,
            'page' => 'waves'
        ])

        @include('partials.tableContent', [
            'clerk' => true,
            'packing' => true,
            'waves' => $waves
        ])

        @include('partials.mainButton', [
            'text' => 'Next',
            'id' => 'selected-packing-wave'
        ])
    </div>

@endsection