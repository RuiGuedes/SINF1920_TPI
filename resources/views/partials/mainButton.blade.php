<div class="text-right my-4">
    @isset($action)
        <form action="@isset($param) {{route($action, ['id_wave' => $param])}} @endisset
            @empty($param) {{route($action)}} @endempty">    
    @endisset
    @empty($action)
        <form id="{{$event}}">
    @endempty

        <button type="submit" class="btn btn-secondary">{{$text}}</button>
    </form>
</div>