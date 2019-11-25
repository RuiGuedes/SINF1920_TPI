<div class="double-sub-container container pl-5 pt-2">
    <div class="sub-header row text-center py-2 ml-5">
        <div class="col-2">Product ID</div>
        <div class="col-4">Name</div>
        <div class="col-2">Zone</div>
        <div class="col-2">Quantity</div>
        <div class="col-2">Stock</div>
    </div>
    @foreach ($items as $item)
        <div class="row text-center py-2 ml-5">
            <div class="col-2">{{ $item['id'] }}</div>
            <div class="col-4">{{ $item['description'] }}</div>
            <div class="col-2">{{ $item['zone'] }}</div>
            <div class="col-2">{{ $item['quantity'] }}</div>
            <div class="col-2">{{ $item['stock'] }}</div>
        </div>        
    @endforeach
</div>