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
            'page' => 'orders',
            'type' => 'Client'
        ])

        @include('partials.tableContent', [
            'orders' => [
                [
                    'id' => '4',
                    'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                    'owner' => 'C0004',
                    'date' => '2019-07-24',
                    'items' => [
                        [
                            'id'=>'56',
                            'description'=>'AK-47',
                            'zone'=>'D4',
                            'quantity' => '2',
                            'stock' => '9'
                        ]
                    ]
                ],
                [
                    'id' => '7',
                    'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                    'owner' => 'C0004',
                    'date' => '2019-07-24',
                    'items' => [
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
        <div class="container my-4">
            <div class="row justify-content-end">
                <form action="">
                    <button type="link" class="btn btn-outline-danger mr-4">Remove</button>
                </form>
                <form action="">
                    <button type="link" class="btn btn-secondary">Pack</button>
                </form>
            </div>
        </div>
    </div>

@endsection