@extends('app')

@section('title', 'Sales Orders')

@section('body')
    @include('partials.mainNavbar')

    @include('partials.subNavbar', [
        'tabs' => [
            [
                'route' => 'picking-waves',
                'name' => 'Picking Waves',
                'active' => true
            ],
            [
                'route' => 'packing-waves',
                'name' => 'Packing'
            ],
            [
                'route' => 'dispatching',
                'name' => 'Dispatching'
            ]
        ]
    ])

    <div class="container-fluid">
        <div class="container-fluid my-4">            
            <div class="row route-boxes justify-content-center">
                @foreach ($zones_list as $item)
                    <div class="route-line pr-md-2 pr-xl-5"><div class="route-box mr-3 mr-md-5"><div class="route-text">{{$item['zone']}}</div></div></div>
                @endforeach
                <div class="route-line"><div class="route-box"><div class="route-text">{{$last_zone['zone']}}</div></div></div>
            </div>                
        </div>

        <div class="container route-zone my-5 justify-content-center">
            @foreach ($zones_list as $item)      

                <div class="row route-boxes justify-content-center">
                    <div class="separ-line col-5"></div>
                    <div class="col-2 text-center">{{$item['zone']}}</div>
                    <div class="separ-line col-5"></div>
                </div>

                @include('partials.tableHeader', [
                    'page' => 'picking-route'
                ])
                
                @foreach ($item['products'] as $product)

                    <div class="row text-center py-2">
                        <div class="col-2">{{$product['section']}}</div>
                        <div class="col-3">{{$product['product']}}</div>
                        <div class="col-2"></div>
                        <div class="col-1">{{$product['quantity']}}</div>
                        <div class="col-2">
                            <div class="quantity buttons_added">
                                <input type="button" value="-" class="minus">
                                <input type="number" step="1" min="0" max="" name="quantity" value="0" 
                                title="Qty" class="input-text qty text" size="4" pattern="" inputmode="">
                                <input type="button" value="+" class="plus">
                            </div>
                        </div>
                        <div class="col-2">
                            <select>
                                <option value="0" selected>No Picked</option>
                                <option value="1">Picked</option>
                                <option value="2">Lack of Stock</option>
                            </select>
                        </div>
                    </div>

                @endforeach
                
            @endforeach

            @include('partials.mainButton', [
                'text' => 'Complete',
                'action' => 'picking-waves'
            ])
        </div>
    </div>
    
@endsection