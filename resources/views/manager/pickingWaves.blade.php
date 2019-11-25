@extends('app')

@section('title', 'Picking Waves')

@section('body')
    @include('partials.mainNavbar')

    @include('partials.subNavbar', [
        'tabs' => [
            [
                'route' => 'manager-sales-orders',
                'name' => 'Sales Orders'
            ],
            [
                'route' => 'manager-purchase-orders',
                'name' => 'Purchase Orders'
            ],
            [
                'route' => 'manager-picking-waves',
                'name' => 'Picking Waves',
                'active' => true
            ],
            [
                'route' => 'manager-replenishment',
                'name' => 'Replenishment'
            ]
        ]
    ])

    <div class="main-container container pt-5">
        @include('partials.tableHeader', [
            'page' => 'waves'
        ])

        @include('partials.tableContent', [
            'waves' => [
                [
                    'id' => '2',
                    'num_orders' => '2',
                    'num_products' => '7',
                    'date' => '2019-10-24',
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
                ],
                [
                    'id' => '1',
                    'num_orders' => '1',
                    'num_products' => '9',
                    'date' => '2019-10-24',
                    'orders' => [
                        [
                            'id' => '8',
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
                            'id' => '6',
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
                ]
            ]
        ])
    </div>
    
@endsection