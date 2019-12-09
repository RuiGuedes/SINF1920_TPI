@extends('app')

@section('title', 'Purchase Orders')

@section('body')
    <div id="success-alert" class="alert alert-success" hidden></div>

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

        <div class="text-right my-4">
            <button id="allocate" type="submit" class="btn btn-secondary">Allocate</button>
        </div>
    </div>

@endsection