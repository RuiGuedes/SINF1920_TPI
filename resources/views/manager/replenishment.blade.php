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
            <div class="row text-center py-2">
                <div class="col-2">{{$product['id']}}</div>
                <div class="col-3">{{$product['description']}}</div>
                <div class="col-1">{{$product['zone']}}</div>
                <div class="col-1">{{$product['stock']}}</div>
                <div class="col-2"> @isset($product['status']) {{$product['status']}} @endisset </div>
                <div class="col-2">
                    <div class="qnt-input input-group">
                        <input type="number" min="0" aria-label="Quantity" 
                            class="form-control text-center" @isset($product['status']) hidden @endisset>
                    </div>
                </div>
                <div class="col-1">
                    <button class="btn btn-outline-secondary"></button>
                </div>
            </div>
            
        @endforeach

        @include('partials.mainButton', [
            'text' => 'Create PO',
            'action' => ''
        ])
    </div>
@endsection