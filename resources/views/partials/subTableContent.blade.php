<div id="row-id-{{$id}}" class="collapse sub-container row">
    @isset($orders)
        <div class="container p-0">
            <div class="sub-header row text-center py-2 ml-5">
                <div class="col-2">Document ID</div>
                <div class="col-4">Order ID</div>
                <div class="col-2">Client ID</div>
                <div class="col-3">Date</div>
            </div>

            @foreach ($orders as $order)
                <div class="row text-center py-2 ml-5">
                    <div class="col-2">{{$order['id']}}</div>
                    <div class="col-4">{{$order['order_id']}}</div>
                    <div class="col-2">{{$order['owner']}}</div>
                    <div class="col-3">{{$order['date']}}</div>
                </div>

                @include('partials.doubleSubTable', [
                    'items' => $order['items']
                ])                
            @endforeach
        </div>
    @endisset

    @empty($orders)
        <div class="container ml-5 px-0 py-1">
            <div class="sub-header row text-center py-2">
                @if ($items_type == 'products')
                    <div class="col-2">Product ID</div>
                    <div class="col-4">Name</div>
                    <div class="col-2">Zone</div>
                    <div class="col-2">Quantity</div>
                    <div class="col-2">Stock</div>
                @endif
            </div>
            
            @foreach ($items as $item)
                @if ($items_type == 'products')
                    <div class="row text-center py-2">
                        <div class="col-2">{{ $item['id'] }}</div>
                        <div class="col-4">{{ $item['description'] }}</div>
                        <div class="col-2">{{ $item['zone'] }}</div>
                        <div class="col-2">{{ $item['quantity'] }}</div>
                        <div class="col-2">{{ $item['stock'] }}</div>
                    </div>
                @endif
            @endforeach
        </div>
    @endempty        
</div>    
