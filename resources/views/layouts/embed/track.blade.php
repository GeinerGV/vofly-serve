@if (isset($embed) && $embed)
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Recorrido de ruta {{$delivery->trackid}}</title>
        <script src="{{ mix('js/app.js') }}" defer></script>
        <link href="{{ mix('css/page/track.css') }}" rel="stylesheet">
    </head>
    <body>
@endif
	<div id="pedido">
	</div>
    <div id="map">
    </div>
    @auth
        @php
            $deliverydata = $delivery
        @endphp
    @else
        @php
            $deliverydata = $delivery
        @endphp
    @endauth
    <script>
        var ta = document.createElement("textarea");
        ta.innerHTML = "{{ json_encode($deliverydata) }}";
        const txt = ta.innerText.replace(/\\/g, "\\\\").replace(/\n/g, "\\n");

        window.DELIVERY_DATA = JSON.parse(txt);
    </script>
    <script src="{{mix("js/track.js")}}"></script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-GqrdlziDJ-rqQJfC7e2PrgzVU1wVPL4&callback=initMap">
    </script>
@if (isset($embed) && $embed)
    </body>
    </html>
@endif
