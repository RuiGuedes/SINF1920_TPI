@extends('app')

@section('title', 'Picking Waves')

@section('body')
    @include('partials.mainNavbar')

    @include('partials.subNavbar', [
        'tabs' => [
            [
                'route' => 'picking-waves',
                'name' => 'Picking Waves',
                'active' => true
            ],
            [
                'route' => 'packing-waves',
                'name' => 'Packing'
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
            'waves' => $waves
        ])

        <div class="text-right my-4">
            <button id="init-picking-route" type="submit" class="btn btn-secondary">Next</button>
        </div>
    </div>

@endsection