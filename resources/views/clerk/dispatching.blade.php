@extends('app')

@section('title', 'Sales Orders')

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
                'name' => 'Packing'
            ],
            [
                'route' => 'dispatching',
                'name' => 'Dispatching',
                'active' => true
            ]
        ]
    ])

    <div class="main-container dispatch container pt-5">
        @include('partials.tableHeader', [
            'page' => 'orders',
            'type' => 'Client'
        ])        

        @include('partials.tableContent', [
            'dispatch' => true,   
            'orders' => [
                [
                    'id' => '4',
                    'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                    'owner' => 'C0004',
                    'date' => '2019-07-24',
                ],
                [
                    'id' => '7',
                    'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                    'owner' => 'C0004',
                    'date' => '2019-07-24',
                ],
                [
                    'id' => '8',
                    'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                    'owner' => 'C0004',
                    'date' => '2019-07-24',
                ]
            ]
        ])
        
        @include('partials.mainButton', [
            'text' => 'Dispatch',
            'action' => 'dispatching'
        ])
    </div>
    
@endsection