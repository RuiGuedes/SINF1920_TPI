<div id="row-id-{{$id}}" class="collapse sub-container row">
    @isset($list_orders)
        <div class="container p-0">
            <div class="sub-header row text-center py-2 ml-5">
                <div class="col-4">Document ID</div>
                <div class="col-3">Client ID</div>
                <div class="col-4">Date</div>
            </div>

            @foreach ($list_orders as $order)
                <div class="row text-center py-2 ml-5">
                    <div class="col-4">{{ $order['id'] }}</div>
                    <div class="col-3">{{ $order['owner'] }}</div>
                    <div class="col-4">{{ $order['date'] }}</div>
                </div>

                @include('partials.doubleSubTable', [
                    'items' => $order['items']
                ])                
            @endforeach
        </div>
    @endisset

    @empty($list_orders)
        <div class="container ml-5 px-0 py-1">
            <div class="sub-header row text-center py-2">
                <div class="col-2">Product ID</div>
                <div class="col-4">Name</div>
                <div class="col-2">Zone</div>
                <div class="col-2">Quantity</div>
                <div class="col-2">Stock</div>
            </div>
            
            @foreach ($items as $item)
                <div class="row text-center py-2">
                    <div class="col-2">{{ $item['id'] }}</div>
                    <div class="col-4">{{ $item['description'] }}</div>
                    <div class="col-2">{{ $item['zone'] }}</div>
                    <div class="col-2">{{ $item['quantity'] }}</div>
                    <div class="col-2">{{ $item['stock'] }}</div>
                </div>
            @endforeach
        </div>
    @endempty        
</div>    
