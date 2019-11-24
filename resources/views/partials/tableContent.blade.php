@foreach ($orders as $order)
    <a href="#row-id-{{ $order['id'] }}" data-toggle="collapse" role="button" aria-expanded="false">
        <div class="row text-center py-2">
            <div class="col-2">{{ $order['id'] }}</div>
            <div class="col-4">{{ $order['order_id'] }}</div>
            <div class="col-2">{{ $order['owner'] }}</div>
            <div class="col-3">{{ $order['date'] }}</div>
            <div class="col-1">
                <button class="btn btn-outline-secondary"></button>
            </div>
        </div>
    </a>
    @include('partials.subTableContent', [
        'items' => $order['items'],
        'id' => $order['id'],
        'items_type' => 'products'
    ])
@endforeach