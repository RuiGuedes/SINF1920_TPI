<div class="header row text-center justify-content-start pb-2">
    @if ($page == 'orders')
        <div class="col-2">Document ID</div>
        <div class="col-4">Order ID</div>
        <div class="col-2">{{ $type }} ID</div>
        <div class="col-3">Date</div>

    @elseif ($page == 'waves')
        <div class="col-3">Wave ID</div>
        <div class="col-3">No. Orders</div>
        <div class="col-3">No. Products</div>
        <div class="col-3">Date</div>
        
    @elseif ($page == 'products')
        <div class="col-2">Product ID</div>
        <div class="col-3">Name</div>
        <div class="col-1">Zone</div>
        <div class="col-1">Stock</div>
        <div class="col-2">Status</div>
        <div class="col-2">Quantity</div>
    
    @endif
</div>