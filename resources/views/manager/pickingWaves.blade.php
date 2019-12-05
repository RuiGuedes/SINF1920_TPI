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

    <div class="main-container container py-5">
        @include('partials.tableHeader', [
            'clerk' => false,
            'page' => 'waves'
        ])

        @include('partials.tableContent', [
            'clerk' => false,
            'waves' => $waves
        ])
    </div>
    
@endsection