<nav class="sub-navbar navbar navbar-expand-sm stick-top">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
        aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            @foreach ($tabs as $tab)
                <a class="nav-item nav-link @isset($tab['active']) {{ 'active' }} @endisset" @empty($blocked)href="{{ route($tab['route']) }}"@endempty>{{$tab['name']}}</a>
            @endforeach
        </div>
    </div>
</nav>