<div class="header row text-center justify-content-start pb-2">
    @switch($page)
        @case('orders')
            <div class="col-4">Document ID</div>
            <div class="col-3">{{ $type }} ID</div>
            <div class="col-4">Date</div>
            @break
        @case('waves')
            <div class="col-{{ $clerk? '2' : '3' }}">Wave ID</div>
            <div class="col-3">No. Orders</div>
            <div class="col-3">No. Products</div>
            <div class="col-3">Date</div>
            @break
        @case('products')
            <div class="col-2">Product ID</div>
            <div class="col-3">Name</div>
            <div class="col-1">Zone</div>
            <div class="col-1">Stock</div>
            <div class="col-2">Status</div>
            <div class="col-2">Quantity</div>
            @break
        @case('picking-route')
            <div class="col-2">Section</div>
            <div class="col-3">Product</div>
            <div class="col-2"></div>
            <div class="col-1">Qnt. Desired</div>
            <div class="col-2">Qnt. Picked</div>
            <div class="col-2">Status</div>
            @break
        @default
            
    @endswitch
</div>