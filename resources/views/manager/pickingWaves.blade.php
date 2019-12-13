@extends('app')

@section('title', 'Picking Waves')

@section('body')
    <div id="success-alert" class="alert alert-success" hidden></div>

    @include('partials.mainNavbar')

    @include('partials.subNavbar', [
        'tabs' => [
            [
                'route' => 'manager-replenishment',
                'name' => 'Replenishment'
            ],
            [
                'route' => 'manager-purchase-orders',
                'name' => 'Purchase Orders'
            ],
            [
                'route' => 'manager-sales-orders',
                'name' => 'Sales Orders'
            ],
            [
                'route' => 'manager-picking-waves',
                'name' => 'Picking Waves',
                'active' => true
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