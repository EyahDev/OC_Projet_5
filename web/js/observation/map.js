function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 46.6, lng: 1.9},
        zoom: 4
    });

    var infoWindow = new google.maps.InfoWindow;

    document.getElementById('geoloc').addEventListener('click', function(e) {
        infoWindow.open(map);

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                document.getElementById('create_observation_latitude').value = position.coords.latitude;
                document.getElementById('create_observation_longitude').value = position.coords.longitude;


                infoWindow.setPosition(pos);
                infoWindow.setContent('Vous êtes ici');
                map.setCenter(pos);

            }, function() {
                handleLocationError(true, infoWindow, map.getCenter());
            });
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
        }
    })
}
function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
        'Erreur : Impossible de vous localiser, vous pouvez entrer manuellement les coordonnées de votre observation.' :
        'Erreur : Votre navigateur ne supporte pas la géolocalisation, veuillez entrer manuellement les coordonnées GPS de votre observation');
}
