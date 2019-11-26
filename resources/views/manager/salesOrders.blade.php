@extends('app')

@section('title', 'Sales Orders')

@section('body')
    @include('partials.mainNavbar')

    @include('partials.subNavbar', [
        'tabs' => [
            [
                'route' => 'manager-sales-orders',
                'name' => 'Sales Orders',
                'active' => true
            ],
            [
                'route' => 'manager-purchase-orders',
                'name' => 'Purchase Orders'
            ],
            [
                'route' => 'manager-picking-waves',
                'name' => 'Picking Waves'
            ],
            [
                'route' => 'manager-replenishment',
                'name' => 'Replenishment'
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
                ],
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
                        ],
                        [
                            'id'=>'58',
                            'description'=>'AK-48',
                            'zone'=>'D4',
                            'quantity' => '2',
                            'stock' => '90'
                        ],
                        [
                            'id'=>'58',
                            'description'=>'Desert Eagle',
                            'zone'=>'B3',
                            'quantity' => '40',
                            'stock' => '300'
                        ]
                    ]
                ]
            ]
        ])
        
        @include('partials.mainButton', [
            'text' => 'Create Wave',
            'action' => 'manager-sales-orders'
        ])
    </div>
@endsection