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
    (function () {
        var ta = document.createElement("textarea");
        ta.innerHTML = "{{ json_encode($deliverydata) }}";
        const txt = ta.innerText.replace(/\\/g, "\\\\");

        window.DELIVERY_DATA = JSON.parse(txt);
    })()
</script>
<script src="{{mix("js/track.js")}}"></script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-GqrdlziDJ-rqQJfC7e2PrgzVU1wVPL4&callback=initMap">
</script>