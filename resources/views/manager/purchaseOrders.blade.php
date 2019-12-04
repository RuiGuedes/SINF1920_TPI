@extends('app')

@section('title', 'Purchase Orders')

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
                'name' => 'Purchase Orders',
                'active' => true
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
            'type' => 'Supplier'
        ])
        
        @include('partials.tableContent', [
            'orders' => $purchases
        ])

        @include('partials.mainButton', [
            'text' => 'Allocate', 
            'id' => 'create-PO'
        ])
    </div>
    
@endsection