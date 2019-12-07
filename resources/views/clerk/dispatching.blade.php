@extends('app')

@section('title', 'Dispatching')

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
                'name' => 'Packing'
            ],
            [
                'route' => 'dispatching',
                'name' => 'Dispatching',
                'active' => true
            ]
        ]
    ])

    <div class="main-container dispatch container pt-5" id="dispatch-div">
        @include('partials.tableHeader', [
            'page' => 'orders',
            'type' => 'Client'
        ])        

        @include('partials.tableContent', [
            'dispatch' => true,   
            'orders' => $orders
        ])
        
        @include('partials.mainButton', [
            'text' => 'Dispatch',
            'action' => 'dispatching'
        ])
    </div>

    <script type="module" src="{{asset('js/dispatching.js')}}"></script>
    
@endsection