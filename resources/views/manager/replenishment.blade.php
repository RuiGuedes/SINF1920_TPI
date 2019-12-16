@extends('app')

@section('title', 'Replenishment')

@section('body')
    <div id="success-alert" class="alert alert-success" hidden></div>

    @include('partials.mainNavbar')

    @include('partials.subNavbar', [
        'tabs' => [
            [
                'route' => 'manager-replenishment',
                'name' => 'Replenishment',
                'active' => true
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
                'name' => 'Picking Waves'
            ]
        ]
    ])

    <div class="main-container with-ground container pt-5">
        @include('partials.tableHeader', [
            'page' => 'products'
        ])
        <div class="products-list">
        @foreach ($products as $product)
            <div class="row text-center py-2 replenishment">
                <div class="col-2">{{$product['product_id']}}</div>
                <div class="col-3 text-left">{{$product['description']}}</div>
                <div class="col-1">{{$product['warehouse_section']}}</div>
                <div class="col-1">{{$product['stock']}}</div>
                <div class="col-2">@isset($product['status']){{$product['status']}}@endisset</div>
                <div class="col-2">
                    <div class="quantity buttons_added" hidden>
                        <input type="button" value="-" class="minus">
                        <input type="number" step="1" min="{{max(1, $product['min_stock'] - $product['stock'])}}" max="{{min($product['max_stock'], $product['max_stock'] - $product['stock'])}}"
                        name="quantity"
                               @if($product['min_stock'] > $product['stock'])
                                    value="{{$product['min_stock']*2 - $product['stock']}}"
                               @else
                                    value="{{max(0, min(1, $product['max_stock'] - $product['stock']))}}"
                               @endif
                               title="Qty" class="input-text qty text" size="4" pattern="" inputmode="">
                        <input type="button" value="+" class="plus">
                    </div>
                </div>
                <div class="col-1">
                    <button class="btn btn-outline-secondary select-multiple"></button>
                </div>
            </div>
        @endforeach
        </div>
        <div class="text-right my-4">
            <button id="create-PO" type="submit" class="btn btn-secondary">Create PO</button>
        </div>
    </div>
@endsection