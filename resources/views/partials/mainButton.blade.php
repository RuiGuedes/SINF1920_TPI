<div class="text-right my-4">
    <form action="@isset($param) {{route($action, ['id_wave' => $param])}} @endisset
        @empty($param) {{route($action)}} @endempty">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <button type="submit" class="btn btn-secondary">{{$text}}</button>
    </form>
</div>