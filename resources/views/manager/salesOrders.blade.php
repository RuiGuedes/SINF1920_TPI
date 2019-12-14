@extends('app')

@section('title', 'Sales Orders')

@section('body')
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
                'name' => 'Sales Orders',
                'active' => true
            ],
            [
                'route' => 'manager-picking-waves',
                'name' => 'Picking Waves'
            ]
        ]
    ])

    <div class="main-container container pt-5">
        @include('partials.tableHeader', [
            'page' => 'orders',
            'type' => 'Client'
        ])

        @include('partials.tableContent', [         
            'orders' => $sales
        ])

        <div class="text-right my-4">
            <button id="create_wave" type="submit" class="btn btn-secondary">Create Wave</button>
        </div>
    </div>
@endsection