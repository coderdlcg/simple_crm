<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey={{$key}}" type="text/javascript"></script>
<script type="text/javascript">
    ymaps.ready(init);

    function init() {
        var myMap = new ymaps.Map("map", {
            center: [{{$company->coordinates->latitude}}, {{$company->coordinates->longitude}}],
            // center: [55.757972, 37.611212],
            zoom: 16
        }, {
            searchControlProvider: 'yandex#search'
        });

        myMap.geoObjects
            .add(new ymaps.Placemark([{{$company->coordinates->latitude}}, {{$company->coordinates->longitude}}], {
                balloonContent: '{{$company->address}}'
            }, {
                preset: 'islands#icon',
                iconColor: '#ea0505'
            }));
    }
</script>

<style>
    #map {
        width: 400px; height: 400px; padding: 10px 0; margin: 0;
    }
</style>

<div id="map"></div>
