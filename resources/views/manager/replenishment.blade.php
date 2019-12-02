@extends('app')

@section('title', 'Replenishment')

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
                'name' => 'Picking Waves'
            ],
            [
                'route' => 'manager-replenishment',
                'name' => 'Replenishment',
                'active' => true
            ]
        ]
    ])

    <div class="main-container with-ground container">
        @include('partials.tableHeader', [
            'page' => 'products'
        ])

        @foreach ($products as $product)
            <div class="row text-center py-2 replenishment">
                <div class="col-2">{{$product['product_id']}}</div>
                <div class="col-3">{{$product['description']}}</div>
                <div class="col-1">{{$product['warehouse_section']}}</div>
                <div class="col-1">{{$product['stock']}}</div>
                <div class="col-2"> @isset($product['status']) {{$product['status']}} @endisset </div>
                <div class="col-2">
                    <div class="qnt-input input-group">
                        <input type="number" min="0" aria-label="Quantity" 
                            class="form-control text-center" hidden>
                    </div>
                </div>
                <div class="col-1">
                    <button class="btn btn-outline-secondary select-multiple"></button>
                </div>
            </div>
            
        @endforeach

        @include('partials.mainButton', [
            'text' => 'Create PO',
            'action' => 'manager-replenishment'
        ])
    </div>
@endsection