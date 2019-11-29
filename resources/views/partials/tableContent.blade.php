@isset($orders)
    @foreach ($orders as $order)
        <div class="row text-center py-2">
            <a class="col-11" @empty($dispatch) href="#row-id-{{ $order['id'] }}" @endempty data-toggle="collapse" role="button" aria-expanded="false">
                <div class="row justify-content-around">
                    <div class="col-2">{{ $order['id'] }}</div>
                    <div class="col-4">{{ $order['order_id'] }}</div>
                    <div class="col-2">{{ $order['owner'] }}</div>
                    <div class="col-3">{{ $order['date'] }}</div>
                </div>
            </a>
            <div class="col-1">
                <button class="btn btn-outline-secondary select-multiple"></button>
            </div>
        </div>
        @empty($dispatch)
            @include('partials.subTableContent', [
                'items' => $order['items'],
                'id' => $order['id']
            ])
        @endempty
    @endforeach
@endisset

@isset($waves)
    @foreach ($waves as $wave)
        <div class="row text-center py-2">
            <a class="col-{{ $clerk? '11' : '12'}}" @empty($packing) href="#row-id-{{$wave['id']}}" @endempty data-toggle="collapse" role="button" aria-expanded="false">
                <div class="row justify-content-around">
                    <div class="col-{{ $clerk? '2' : '3'}}">{{$wave['id']}}</div>
                    <div class="col-3">{{$wave['num_orders']}}</div>
                    <div class="col-3">{{$wave['num_products']}}</div>
                    <div class="col-3">{{$wave['date']}}</div>
                </div>
            </a>
            @if ($clerk)
                <div class="col-1">
                    <button class="btn btn-outline-secondary select-one"></button>
                </div>
            @endif
        </div>
        @isset($wave['orders'])
            @include('partials.subTableContent', [
                'id' => $wave['id'],
                'list_orders' => $wave['orders']
            ])
        @endisset

        @isset($wave['items'])
            @include('partials.subTableContent', [
                'id' => $wave['id'],
                'items' => $wave['items']
            ])
        @endisset
    @endforeach
@endisset