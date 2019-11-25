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
            'waves' => [
                [
                    'id' => '2',
                    'num_orders' => '2',
                    'num_products' => '7',
                    'date' => '2019-10-24',
                ],
                [
                    'id' => '1',
                    'num_orders' => '1',
                    'num_products' => '9',
                    'date' => '2019-10-24',
                ]
            ]
        ])

        @include('partials.mainButton', [
            'text' => 'Next',
            'action' => 'packing'
        ])
    </div>

@endsection