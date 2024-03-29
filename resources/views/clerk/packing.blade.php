@extends('app')

@section('title', 'Packing')

@section('body')
    @include('partials.mainNavbar')

    @include('partials.subNavbar', [
        'blocked' => true,
        'tabs' => [
            [
                'name' => 'Picking Waves'
            ],
            [
                'name' => 'Packing',
                'active' => true
            ],
            [
                'name' => 'Dispatching'
            ]
        ]
    ])

    <div class="main-container container pt-5">
        @include('partials.tableHeader', [
            'page' => 'orders',
            'type' => 'Client'
        ])

        @include('partials.tableContent', [
            'packing' => true,
            'orders' => $orders
        ])
        <div class="container my-4">
            <div class="row justify-content-end">
                <button id="remove-packing-order" type="link" class="btn btn-outline-danger mr-4">Remove</button>
                <button id="pack-order" type="link" class="btn btn-secondary">Pack</button>
            </div>
        </div>
    </div>

@endsection