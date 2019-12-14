<div class="double-sub-container container pt-2">
    <div class="sub-header row text-center py-2">
        <div class="col-2">Product ID</div>
        <div class="col-5 text-left">Name</div>
        <div class="col-1">Zone</div>
        <div class="col-2 pr-5 text-right">Quantity</div>
        <div class="col-2 pr-5 text-right">Stock</div>
    </div>
    @foreach ($items as $item)
        <div class="row text-center py-2">
            <div class="col-2">{{ $item['id'] }}</div>
            <div class="col-5 text-left">{{ $item['description'] }}</div>
            <div class="col-1">{{ $item['zone'] }}</div>
            <div class="col-2 pr-5 text-right">{{ $item['quantity'] }}</div>
            <div class="col-2 pr-5 text-right">{{ $item['stock'] }}</div>
        </div>        
    @endforeach
</div>