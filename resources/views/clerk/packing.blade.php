@extends('app')

@section('title', 'Packing')

@section('body')
    @include('partials.mainNavbar')

    @include('partials.subNavbar', [
        'tabs' => [
            [
                'route' => 'picking-waves',
                'name' => 'Picking Waves'
            ],
            [
                'route' => 'packing-waves',
                'name' => 'Packing',
                'active' => true
            ],
            [
                'route' => 'dispatching',
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
            'orders' => $orders
        ])
        <div class="container my-4">
            <div class="row justify-content-end">
                <form action="">
                    <button type="link" class="btn btn-outline-danger mr-4">Remove</button>
                </form>
                <form action="">
                    <button type="link" class="btn btn-secondary">Pack</button>
                </form>
            </div>
        </div>
    </div>

@endsection