@php
    $asides = [
        ["key"=>"dashboard", "txt"=>"Dashboard"],
        ["key"=>"usuarios", "txt"=>"Usuarios"], 
        ["key"=>"drivers", "txt"=>"Drivers"], 
        ["key"=>"pedidos", "txt"=>"Pedidos"],
    ];
    $active = request()->path()
@endphp

<aside class="dash-aside p-2 sticky-top">
    <div class="list-group">
        @foreach ($asides as $item)
            <a href="/{{$item["key"]}}" class='list-group-item list-group-item-action{{($active==$item["key"] ? " active" : "")}}'>{{$item["txt"]}}</a>
        @endforeach
    </div>
</aside>