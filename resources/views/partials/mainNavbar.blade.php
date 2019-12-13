<nav class="main-navbar navbar navbar-expand stick-top pt-3">
    <div class="logo-line col align-self-start">
        <a href="{{route('layout')}}">Layout</a>
    </div>
    <div class="col-5 col-sm-3 col-md-3 col-xl-2 text-center">
        <a class="company-name"
            @if(\App\User::isManager(\Illuminate\Support\Facades\Auth::id()))
                href="{{route('manager-sales-orders')}}"
            @else
                href="{{route('picking-waves')}}"
            @endif
        >TPI</a>
    </div>
    <div class="logo-line col text-right align-self-start">
        <a href="{{ route('logout') }}">Logout</a>
    </div>
</nav>