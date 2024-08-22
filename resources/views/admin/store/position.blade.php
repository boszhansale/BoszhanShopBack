@extends('admin.layouts.index')

@section('content-header-title')
    <a href="{{route('admin.user.show',$user->id)}}">{{$user->name}}</a>
@endsection


@section('content')
    <h6>Количество торговых точек: {{$user->stores->count()}}</h6>
    <h6>Количество торговых точек с кординатами: {{count($positions)}}</h6>
    <br>
    <div class="row">

        {{--        <div class="col-2">--}}
        {{--            <form action="{{route('admin.user.position',$user->id)}}">--}}
        {{--                <div class="form-group">--}}
        {{--                    <label for="">Дата</label>--}}
        {{--                    <input type="date" name="date" class="form-control" value="{{now()->format('Y-m-d')}}">--}}

        {{--                </div>--}}
        {{--                <div class="form-group">--}}
        {{--                    <button class="btn btn-primary">показать</button>--}}
        {{--                </div>--}}
        {{--            </form>--}}
        {{--        </div>--}}
        <div class="col-md-12">
            <div id="map" style="height: 700px">

            </div>
        </div>
    </div>

@endsection



@section('js')
    <script>
        var map;

        var pathCoordinates = JSON.parse('@json($positions)');


        var startLngLat = JSON.parse('@json($user->stores()->whereNotNull('lat')->latest()->first(['lat','lng']))');

        function initMap() {
            var mapLayer = document.getElementById("map");
            var centerCoordinates = new google.maps.LatLng(startLngLat);
            var defaultOptions = {
                center: centerCoordinates,
                zoom: 12
            }
            map = new google.maps.Map(mapLayer, defaultOptions);

            // google.maps.event.addListener(map, 'click', function (event) {
            //     // pathCoordinates.push({
            //     //     lat : event.latLng.lat(),
            //     //     lng : event.latLng.lng()
            //     // });
            //     //
            //     // new google.maps.Marker({
            //     //     position : new google.maps.LatLng(event.latLng),
            //     //     map : map,
            //     //     title : 'test'
            //     // });
            //
            // });

            for (let pos of pathCoordinates) {
                console.log(pos)
                let marker = new google.maps.Marker({
                    position: new google.maps.LatLng(pos),
                    map: map,
                    title: pos.name,
                    url: window.origin + '/admin/store/show/' + pos.id
                });

                google.maps.event.addListener(marker, 'click', function () {
                    window.location.href = this.url;
                });
            }
        }


    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDYjhd-JDSwGWuiZBp_27RfSMOSCB-mTBQ&callback&callback=initMap">
    </script>

@endsection

