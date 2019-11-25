@extends('app')

@section('title', 'Sales Orders')

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
            'waves' => [
                [
                    'id' => '2',
                    'num_orders' => '2',
                    'num_products' => '7',
                    'date' => '2019-10-24',
                    'items' => [
                        [
                            'id'=>'324',
                            'description'=>'MP-7',
                            'zone'=>'D4',
                            'quantity' => '2',
                            'stock' => '9'
                        ],
                        [
                            'id'=>'56',
                            'description'=>'AK-47',
                            'zone'=>'D4',
                            'quantity' => '2',
                            'stock' => '9'
                        ],
                        [
                            'id'=>'508',
                            'description'=>'AK-48',
                            'zone'=>'D4',
                            'quantity' => '2',
                            'stock' => '9'
                        ]
                    ]
                ],
                [
                    'id' => '1',
                    'num_orders' => '1',
                    'num_products' => '9',
                    'date' => '2019-10-24',
                    'items' => [
                        [
                            'id'=>'34',
                            'description'=>'M-7',
                            'zone'=>'D4',
                            'quantity' => '2',
                            'stock' => '9'
                        ],
                        [
                            'id'=>'56',
                            'description'=>'AK-47',
                            'zone'=>'D4',
                            'quantity' => '2',
                            'stock' => '9'
                        ],
                        [
                            'id'=>'58',
                            'description'=>'AK-48',
                            'zone'=>'D4',
                            'quantity' => '2',
                            'stock' => '9'
                        ]
                    ]
                ]
            ]
        ])
        @include('partials.mainButton', [
            'text' => 'Next',
            'action' => 'picking-route'
        ])
    </div>

@endsection